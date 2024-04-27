@extends('layouts.app')

@push('style')
<style type="text/css">
    .my-active span{
        background-color: #5cb85c !important;
        color: white !important;
        border-color: #5cb85c !important;
    }
    ul.pager>li {
        display: inline-flex;
        padding: 5px;
    }
</style>
@endpush
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach ($newsdata as $news)
                <a style="text-decoration: none" href="{{ $news['url'] }}">
                    <div class="card mt-5 ml-5" style="width:90%">
                        <div class="row center">
                            <div class="col-sm-6">
                                <img src="{{ $news['urlToImage'] }}" class="card-img-top" alt="..."
                                    style="width:90%;height:90%">
                            </div>
                            <div class="col-sm-6">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $news['title'] }}</h5>
                                    <p class="card-text">{{ $news['content'] }}</p>
                                    <p class="card-text"><small class="text-muted"> Publish Date:
                                            {{ date('d-m-Y', strtotime($news['publishedAt'])) }}</small></p>
                                    <p class="card-text"><small class="text-muted"> Author:
                                            {{ $news['author'] }}</small>
                                    </p>

                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
                {{ $newsdata->links() }}
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $.ajax({
            url: 'https://randomuser.me/api/',
            dataType: 'json',
            success: function(data) {
                console.log(data);
            }
        });
    </script>
@endsection
