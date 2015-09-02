{{-- partners --}}
<div id="partners" class="text-content">
    <div class="container">
        @foreach($partners as $partner)
            <div>
                <a class="new_window" href="{{ $partner->url }}" title="{{ $partner->name }}">
                    <img height="60" src="{{ url('/').$partner->logo }}" alt="{{ $partner->title }}">
                </a>
            </div>
        @endforeach
    </div>
</div>