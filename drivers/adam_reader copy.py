from __future__ import print_function
from datetime import datetime
import psycopg2
import psycopg2.extras
from pymodbus.constants import Endian
from pymodbus.payload import BinaryPayloadDecoder
from pymodbus.client import ModbusTcpClient
from random import randint
import os
from dotenv import load_dotenv
load_dotenv("../.env")

def get_value(ip_address,port,address):
    try:
        client = ModbusTcpClient(ip_address, port=port,timeout=3)  # Specify the port.
        connection = client.connect()
        if(connection == False):
            return -222
        request = client.read_holding_registers(int(address), 1)
        #print(request.registers)
        raw = request.registers[0] if request.registers else -1
        client.close()
        return raw
    except Exception as e:
        print("Get Value Analog Input Erorr: ",e)
        return -1
def connect_db():
    try:
        host = os.getenv("DB_HOST")
        user = os.getenv("DB_USER")
        password = os.getenv("DB_PASSWORD")
        database = os.getenv("DB_NAME")
        conn = psycopg2.connect(host=host, user=user, password=password, database=database)
        return conn
    except Exception as e:
        print("Connection Error: ",e)
        return None
        
def get_sensors():
    try:
        conn = connect_db()
        cursor = conn.cursor(cursor_factory=psycopg2.extras.RealDictCursor)
        sql = "SELECT sensors.id,sensors.stack_id,sensors.name,extra_parameter,formula,analyzer_ip,port,stacks.oxygen_reference FROM sensors LEFT JOIN stacks ON sensors.stack_id = stacks.id where sensors.deleted_at is null ORDER BY id"
        cursor.execute(sql)
        result = cursor.fetchall()
        return result
    except Exception as e:
        print("Connection Error: ",e)
        return None
def update_value(sensorId, measured,raw,isInsertLog = True):
    try:
        global now
        conn = connect_db()
        cursor = conn.cursor()
        # Insert or Update if row exists in sensor_value_logs
        sql = "SELECT id FROM sensor_value_logs WHERE sensor_id = {}".format(sensorId)
        cursor.execute(sql)
        result = cursor.fetchall()
        if len(result) > 0:
            sql = "UPDATE sensor_value_logs SET measured = {}, raw = {}, updated_at = now() WHERE sensor_id = {}".format(measured,raw,sensorId)
            cursor.execute(sql)
            conn.commit()
            cursor.close()
        else:
            sql = "INSERT INTO sensor_value_logs(sensor_id,measured,raw,created_at) VALUES ({},{},{},now())".format(sensorId,measured,raw)
            cursor.execute(sql)
            conn.commit()
            cursor.close()

        cursor = conn.cursor()
        if(isInsertLog):
            # Insert History Data
            sql = "INSERT INTO sensor_values(sensor_id,measured,raw,is_averaged,created_at) VALUES ({},{},{},0,'{}')".format(sensorId,measured,raw,now)
            cursor.execute(sql)
            conn.commit()
            cursor.close()
        return True
    except Exception as e:
        print("Update Value Error: ",e)
        return None
def update_value_corrective(sensorId,measured,corrected,raw):
    try:
        global now
        conn = connect_db()
        cursor = conn.cursor()
        # Insert or Update if row exists in sensor_value_rca_logs
        sql = "SELECT id FROM sensor_value_rca_logs WHERE sensor_id = {}".format(sensorId)
        cursor.execute(sql)
        result = cursor.fetchall()
        if len(result) > 0:
            sql = "UPDATE sensor_value_rca_logs SET measured = {}, corrected = {}, raw={}, updated_at = now() WHERE sensor_id = {}".format(measured,corrected,raw,sensorId)
            cursor.execute(sql)
            conn.commit()
            cursor.close()
        else:
            sql = "INSERT INTO sensor_value_rca_logs(sensor_id,measured,raw,corrected,created_at) VALUES ({},{},{},{},now())".format(sensorId,measured,raw,corrected)
            cursor.execute(sql)
            conn.commit()
            cursor.close()

        cursor = conn.cursor()
        # Insert History Data
        sql = "INSERT INTO sensor_value_rca(sensor_id,measured,corrected,raw,created_at) VALUES ({},{},{},{},'{}')".format(sensorId,measured,corrected,raw,now)
        cursor.execute(sql)
        conn.commit()
        cursor.close()
        return True
    except Exception as e:
        print("Update Value Error: ",e)
        return None
    
def get_value_o2(stack_id):
    try:
        conn = connect_db()
        cursor = conn.cursor(cursor_factory=psycopg2.extras.RealDictCursor)
        sql = "SELECT measured FROM sensor_value_logs WHERE sensor_id in (select id from sensors where extra_parameter=1 and is_show=1 and stack_id = {} and deleted_at is null)".format(stack_id)
        cursor.execute(sql)
        result = cursor.fetchone()
        if result:
            return result["measured"]
        return None
    except Exception as e:
        print("Get Value O2 Error : ",e)
        return None

def get_config():
    try:
        conn = connect_db()
        cursor = conn.cursor(cursor_factory=psycopg2.extras.RealDictCursor)
        sql = "SELECT * FROM configurations WHERE id=1"
        cursor.execute(sql)
        result = cursor.fetchone()
        return result
    except Exception as e:
        print("Connection Error: ",e)
        return None
    
def main():
    global now
    now =  datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    parameters = get_sensors()
    config = get_config()
    for parameter in parameters:
        try:
            analyzer_ip = parameter["analyzer_ip"] # IP Address Modbus
            formula = parameter["formula"] # Formula
            address_list = parameter["port"].split("|") # Array of Port & AIN
            port = address_list[0] # Port Modbus
            address = address_list[1] # Main Address
            _raw = [] # Initialize Raw Value from Analog Input
            
            for index,_address in enumerate(address_list): # Loop AIN
                if(index > 0):
                    _raw.append(get_value(analyzer_ip,port,_address))
           # print(_raw)
            
            # Get Raw Value
            raw = round(2.44144E-4*get_value(analyzer_ip,port,address) + 4,2)
            #raw = 10
            # print(port, address, raw)
            try:
                measured = eval(parameter["formula"]) if parameter["formula"] else raw
            except Exception as e:
                measured = -1
            #print(measured)
            # Debug
            # print("Sensor: "+str(parameter['name'])) 
            # print("Formula: "+str(parameter['formula'])) 
            # print("Raw: "+str(raw)) 
            # print("Measured: "+str(measured))
            if(config["is_rca"] == 1):
                if(parameter["extra_parameter"] == 2):
                    o2_value = get_value_o2(parameter["stack_id"])
                    corrective = 0
                    o2_reference = parameter["oxygen_reference"]
                    if(o2_value != None):
                        corrective = round(measured * (21 - o2_reference) / (21-o2_value),2)
                elif(parameter["extra_parameter"] == 1):
                    corrective = measured
                if(measured != -1):
                    update_value_corrective(parameter["id"],measured,corrective,raw)
                
            if(measured != -1):
                update_value(parameter["id"],measured,raw)
            else:
                update_value(parameter["id"],measured,raw,False)
            
        except Exception as e:
            update_value(parameter["id"],-2,-2,False)
            None

if __name__ == "__main__":
    main()