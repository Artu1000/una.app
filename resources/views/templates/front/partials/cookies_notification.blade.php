<div id="cookies_notification">
    <div class="display-table">
        <div class="table-cell text-center">
            <span>
                {!! trans('global.cookies_notification.announce', ['app' => config('settings.app_name_' . config('app.locale'))]) !!}
            </span>
            <button id="accept_cookies" class="btn btn-success">
                {!! config('settings.success_icon') !!} {{ trans('global.cookies_notification.accept') }}
            </button>
            @if(isset($terms_and_conditions) && $terms_and_conditions instanceof App\Models\Page)
                {{-- terms and conditions--}}
                <span>
                    <a class="btn btn-primary spin-on-click"
                       href="{{ route('page.show', ['slug' => $terms_and_conditions->slug]) }}"
                       title="{{ strip_tags($terms_and_conditions->title) }}">
                        <i class="fa fa-question-circle" aria-hidden="true"></i> {{ trans('global.cookies_notification.more') }}
                    </a>
                </span>
            @endif
        </div>
    </div>
</div>