<div class="catalog slideshow-container text-center">
    <a href="javascript:void(null);" id="catalog-slide-right">&#10095;</a>
    <ul class="catalog slides p-0" style="list-style: none;display: inline-flex;position: relative;width: 100%;overflow:hidden;justify-content: center;">
        @foreach($galls as $galls=>$gall)
        <li class="catalog slide col-12">
            <img src="{{ url("ecom/cataloge/{$gall->images}") }}" alt="{{ $gall->images }}" class="img-thumbnail img-responsive" style="height:200px;min-height:auto;" loading="lazy">
        </li>
        @endforeach
        <!-- Add more images if needed -->
    </ul>
    <a href="javascript:void(null);" id="catalog-slide-left">&#10094;</a>
</div>