@php
    use Carbon\Carbon;
    $date = Carbon::now();
    $count = 0;
@endphp

@foreach ($charts as $name => $chart)
    <div class="col-6 mb-3">
        <div class="card shadow-sm border-dark">
            <div class="card-body">
                <h5 class="card-title fw-bold d-flex justify-content-between">
                    <span> {{ $chartNames[$count] }} </span>
                    <select class="form-select border-secondary border-opacity-50  w-25"
                        onchange="updateChart{{$name}}(this.value, '{{ $name }}')">
                        @foreach ($months as $i => $month)
                            <option value="{{ $i + 1 }}" {{ $date->month == $i + 1 ? 'selected' : '' }}>
                                {{ $month }} </option>
                        @endforeach
                    </select>
                </h5>
                <div id="chart-{{ $name }}">
                    {!! $chart->container() !!}
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/vue"></script>
    <script>
        var original_api_url_{{$name}} = '';
        function updateChart{{$name}}(value, name) {
            if(!original_api_url_{{$name}}) {
                original_api_url_{{$name}} = {{ $chart->id }}_api_url;
            }

            {{ $chart->id }}_refresh(original_api_url_{{$name}} + '/update' + "?month=" + value);
        }
    </script>
    {!! $chart->script() !!}

    @php
        $count++;
    @endphp
@endforeach

<script src=https://cdnjs.cloudflare.com/ajax/libs/echarts/4.0.2/echarts-en.min.js charset=utf-8></script>
