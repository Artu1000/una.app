{{-- partners --}}
<div id="partners" class="text-content row">
    <div class="container">
        @foreach($partners as $partner)
            <div>
                <a class="new_window" href="{{ $partner->url }}" title="{{ $partner->name }}">
                    <img height="60" src="{{ $partner->imagePath($partner->logo, 'logo', 'logo') }}" alt="{{ $partner->name }}">
                </a>
            </div>
        @endforeach
    </div>
</div>