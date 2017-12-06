@extends('apidoc::common')
@section('head')
<style type="text/css">
    .title{text-align: center;margin: 100px auto;}
    .module{text-align: center;margin: 20px auto;}
    .search {position: relative;}
    .search .typeahead{width: 80%;font-size: 18px;line-height: 1.3333333;}
    .search input{width: 80%;display: inline-block;}
    .search button{height: 48px;width: 18%; margin-top: -5px; text-transform: uppercase;font-weight: bold;font-size: 14px; }
</style>
<script src="{{ Request::root() }}/apidoc/js/bootstrap-typeahead.js" type="text/javascript"></script>
@stop

@section('content')
<div class="container">
    <div class="title">
        <h1>{{ $title }}</h1>
    </div>

    <div class="module">
        <ul class="nav nav-pills">
            @foreach($module as $group)
                @if(isset($group['children']))
                    <li role="presentation" class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ $group['title'] or '' }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            @foreach($group['children'] as $val)
                            <li role="presentation"><a href="#" module>{{ $val['title'] or '' }}</a></li>
                            @endforeach
                    </ul>
                    </li>
                @else
                <li role="presentation"><a href="#" module>{{ $group['title'] or '' }}</a></li>
                @endif
            @endforeach
        </ul>
    </div>

    <div class="form-group search">
        <input  id="search_input" class="form-control input-lg  ng-pristine ng-empty ng-invalid ng-invalid-required" type="text" placeholder="接口名称/接口信息/作者/接口地址" data-provide="typeahead" autocomplete="off">
        <button class="btn btn-lg btn-success" id="search" data-loading-text="Loading..." autocomplete="off"><i class="glyphicon glyphicon-search"></i> 搜 素</button>
    </div>

    <div class="result">
        <div class="list-group"></div>
    </div>
</div>
@stop

@section('footer')
<script type="text/javascript">
    $(function () {
        $('#search_input').typeahead({
            source: function (query, process) {
                $.getJSON("{{ Request::root() }}/doc/search", { "query": query }, function(data){
                    var items = [];
                    $.each(data, function(index, doc){
                        items.push(doc.title);
                    });
                    process(items);
                });
            }
        });
        $('#search').click(function(){
            var query = $('#search_input').val();
            var $btn = $(this).button('loading');
            $.ajax({
                type: "GET",
                url: "{{ Request::root() }}/doc/search?query="+query,
                dataType:'json',
                success: function (data) {
                    $(".result .list-group").html('');
                    $.each(data, function(index, doc){
                        var item = '<a href="javascript:void(0)" class="list-group-item" name="'+ doc.name +'" title="'+ doc.title +'" doc>' +
                            '<span class="badge">'+ doc.author +'</span>' +
                            ''+ doc.title + '<span class="text-primary">('+ doc.url +')</span>'+'</a>';
                        $(".result .list-group").append(item);
                    });
                    $btn.button('reset');
                },
                complete : function(XMLHttpRequest,status){
                    if(status == 'timeout'){
                        alert("网络超时");
                        $btn.button('reset');
                    }
                }
            });
        });

        $('a[module]').click(function(){
            if(window.parent)
            {
                var zTree = window.parent.zTree;
                var node = zTree.getNodeByParam("title", $(this).text());
                zTree.selectNode(node);
            }
        });

        $(".result .list-group").on('click', 'a[doc]', function(){
            if(window.parent)
            {
                var zTree = window.parent.zTree;
                var node = zTree.getNodeByParam("name", $(this).attr('name'));
                window.parent.loadText(node.tId, $(this).attr('title'), $(this).attr('name'));
                zTree.selectNode(node);
            }
        });
     });
</script>
@stop