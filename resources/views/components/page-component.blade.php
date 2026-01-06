
@if ($datas)

    @php

        $breadcrumbKey = array_key_first($datas) ;
        $breadcrumbData = $datas[$breadcrumbKey] ;

    @endphp

    @if ($breadcrumbKey)

        @if($breadcrumbKey == 'Breadcrumb')

            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1> {{ $breadcrumbData['title'] }} </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="url('/')"> Home </a></li>
                                @foreach ($breadcrumbData['sub_title'] as $breadcrumb)
                                    @if ($loop->last)
                                        <li class="breadcrumb-item active">{{ $breadcrumb['title'] }}</li>
                                    @else
                                        <li class="breadcrumb-item"><a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a></li>
                                    @endif
                                @endforeach

                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

        @endif
    @endif

@endif
