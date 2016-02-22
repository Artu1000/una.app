{{-- language choice --}}
@if(config('settings.multilingual'))
    <ul class="nav nav-tabs inputs_language_selectors">
        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <li role="presentation" @if($localeCode === config('app.locale'))class="active" @endif>
                <a href="{{ $localeCode }}" title="{{ $properties['native'] }}">
                    <div class="display-table">
                        <div class="table-cell flag">
                            <img width="20" height="20" class="img-circle" data-locale="{{ $localeCode }}" src="{{ url('img/flag/' . $localeCode . '.png') }}" alt="{{ $localeCode }}">
                        </div>
                        &nbsp;
                        <div class="table-cell">
                            {{ $properties['native'] }}
                        </div>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
@endif