@extends("layouts.theme")
@section("title","Monitoring Realtime")

@section("content")
<div class="container py-4" style="min-height: 85vh">

    <div class="d-flex flex-wrap justify-content-between align-items-center pb-3 mb-4 border-bottom">
        <div>
            <h2 class="fw-bold text-dark m-0">Monitoring Dashboard</h2>
            <div class="text-muted mt-1">
                <i class="fas fa-server me-1"></i> {{ $config->name }}
                <span class="mx-2">/</span>
                <span class="badge bg-secondary">{{ $stack->name }}</span>
            </div>
        </div>

        <div class="d-flex gap-2 align-items-center mt-3 mt-md-0">
            @if($config->is_rca == 1 && $config->rca_stack != $stack->id)
                <div class="alert alert-warning py-1 px-3 m-0 d-flex align-items-center small fw-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i> RCA Active (Other Stack)
                </div>
            @endif

            <button type="button"
                    data-bs-toggle="modal"
                    data-bs-target="#modal-confirm-rca"
                    id="btn-start-rca"
                    {{ ($config->is_rca == 1 && $config->rca_stack != $stack->id) ? 'disabled' : '' }}
                    class="btn {{ ($config->is_rca == 1 && $config->rca_stack == $stack->id) ? 'btn-danger' : 'btn-primary' }} btn-sm px-3 fw-bold">
                <i class="fas fa-{{ ($config->is_rca == 1 && $config->rca_stack == $stack->id) ? 'stop-circle' : 'play-circle' }} me-1"></i>
                {{ ($config->is_rca == 1 && $config->rca_stack == $stack->id) ? 'Stop RCA' : 'Start RCA' }}
            </button>

            <div class="dropdown">
                <button class="btn btn-light border shadow-sm dropdown-toggle btn-sm px-3" type="button" data-bs-toggle="dropdown">
                    {{ $stack->name }}
                </button>
                <ul class="dropdown-menu shadow-sm border-0">
                    @foreach ($stacks as $_stack)
                    <li><a class="dropdown-item {{ $stack->id == $_stack->id ? 'active' : '' }}" href="{{ url("monitoring/$_stack->id") }}">{{ $_stack->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @if (count($sensors) > 0)
            @foreach ($sensors as $sensor)
            <div class="col-xl-3 col-lg-4 col-md-6">

                <div data-id="{{ $sensor->id }}" class="card h-100 border border-secondary border-opacity-25 shadow-sm rounded-4 sensor-card status-normal">
                    <div class="card-body d-flex flex-column justify-content-between p-4">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-uppercase text-secondary fw-bold small ls-1" style="letter-spacing: 1px;">
                                {{ $sensor->name }}
                            </span>
                            <div class="status-indicator text-success">
                                <div class="spinner-grow spinner-grow-sm" role="status"></div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex align-items-baseline text-dark">
                                <span data-id="{{ $sensor->id }}" class="fw-bold display-5 me-2 measured-value">-</span>
                                <span class="text-muted fw-bold fs-6">{{ $sensor->unit->name }}</span>
                            </div>

                            @if ($config->is_rca == 1 && $config->rca_stack == $stack->id)
                            <div class="alert alert-danger py-1 px-2 mt-2 mb-0 d-flex justify-content-between align-items-center border-0 bg-danger bg-opacity-10 text-danger">
                                <span style="font-size: 0.7rem" class="fw-bold">RCA ACTIVE</span>
                                <strong class="small">
                                    <span data-id="{{ $sensor->id }}" class="corrected-value">0</span> {{ $sensor->unit->name }}
                                </strong>
                            </div>
                            @endif
                        </div>

                        <div class="border-top pt-3 mt-3 d-flex justify-content-between align-items-center text-muted small">
                            <span>Input Signal</span>
                            <span class="font-monospace fw-bold text-dark"><span data-id="{{ $sensor->id }}" class="raw-value">-</span></span>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="col-12 text-center py-5">
                <div class="text-muted opacity-25 mb-3"><i class="fas fa-plug fa-4x"></i></div>
                <h4 class="text-secondary fw-normal">No Sensors Connected</h4>
            </div>
        @endif
    </div>
</div>
@endsection

@section("modal")
<div class="modal fade" id="modal-confirm-rca" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-4">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold ps-2">Confirm Action</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form action="{{ route("monitoring.rca.store") }}" id="form-rca" method="post">
          @csrf @method("POST")
          <input type="hidden" name="stack_id" value="{{ $stackId }}">

          <div class="alert {{ ($config->is_rca == 1 && $config->rca_stack == $stack->id) ? 'alert-danger' : 'alert-primary' }} d-flex align-items-center" role="alert">
              <i class="fas fa-info-circle me-3 fs-4"></i>
              <div>
                  Switch RCA Mode for <strong>{{ $stack->name }}</strong> to
                  <strong class="text-uppercase">{{ ($config->is_rca == 1 && $config->rca_stack == $stack->id) ? 'OFF' : 'ON' }}</strong>?
              </div>
          </div>

          <button type="submit" class="btn w-100 py-2 fw-bold {{ ($config->is_rca == 1 && $config->rca_stack == $stack->id) ? 'btn-danger' : 'btn-primary' }}">
              Confirm
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section("js")
<script>
  $(document).ready(function() {
    function fetchValues() {
      $.ajax({
          url : `{{ url("api/values/$stackId") }}`,
          dataType : "json",
          success : function(response){
              if(response?.success){
                const {data} = response
                data.forEach(item => {
                  const $card = $(`.sensor-card[data-id="${item.id}"]`);
                  const $indicator = $card.find('.status-indicator');
                  const $valueText = $card.find('.measured-value');

                  const measured = parseFloat({{ ($config->is_rca == 1 && $config->rca_stack == $stack->id) ? "item?.value_rca?.measured" : "item?.value?.measured" }}) ?? -999;
                  const raw = parseFloat({{ ($config->is_rca == 1 && $config->rca_stack == $stack->id) ? "item?.value_rca?.raw" : "item?.value?.raw" }}) ?? -999;

                  @if($config->is_rca == 1 && $config->rca_stack == $stack->id)
                    const corrected = parseFloat(item?.value_rca?.corrected ?? measured);
                    $(`.corrected-value[data-id='${item.id}']`).text(corrected.toFixed(2));
                  @endif

                  $valueText.text(measured.toFixed(2));
                  $(`.raw-value[data-id='${item.id}']`).text(raw.toFixed(2));

                  // UPDATE LOGIC JS:
                  // Saat normal: border-secondary (abu) + opacity-25
                  // Saat critical: border-danger (merah) + opacity penuh (hapus class opacity)
                  if(measured <= 0){
                    $card.removeClass('border-secondary border-opacity-25 shadow-sm').addClass('border-danger shadow');
                    $indicator.removeClass('text-success').addClass('text-danger');
                    $valueText.addClass('text-danger');
                  } else {
                    $card.removeClass('border-danger shadow').addClass('border-secondary border-opacity-25 shadow-sm');
                    $indicator.removeClass('text-danger').addClass('text-success');
                    $valueText.removeClass('text-danger');
                  }
                })
              }
          }
      })
      setTimeout(fetchValues, 1000);
    }
    fetchValues();

    $("#form-rca button").click(function(e){
      e.preventDefault();
      const $btn = $(this);
      $btn.prop("disabled",true);
      let i=3;
      const interval = setInterval(() => {
        if(i < 1){
          clearInterval(interval);
          $btn.prop("disabled",false);
          $btn.html("Processing...");
          $("#form-rca").submit();
        } else {
          $btn.html(`Wait ${i}s...`);
        }
        i--;
      }, 1000);
    })
  })
</script>
@endsection
