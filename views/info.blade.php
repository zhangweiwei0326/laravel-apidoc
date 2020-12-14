@extends('apidoc::common')
@section('head')
<link href='{{ Request::root() }}/apidoc/css/json.css' rel='stylesheet' type='text/css'>
<script src="{{ Request::root() }}/apidoc/js/json.js" type="text/javascript"></script>
@stop

@section('content')
<div class="container">
    <div class="jumbotron">
        <p class="bg-success" style="font-size: 18px;">文档地址：{{ Request::root() }}/doc?name={{ $doc['name'] }}</p>
        <h2>接口：{{ isset($doc['title']) ? $doc['title'] : '请设置title注释' }}</h2>
        <p>接口地址：{{ isset($doc['url']) ? $doc['url'] : '请设置url注释' }} <span class="label label-success">{{ isset($doc['method']) ? $doc['method'] : 'GET' }}</span></p>
        <p class="text-primary">{{ isset($doc['title']) ? $doc['title'] : '请设置title注释' }} -- {{ isset($doc['author']) ? $doc['author'] : '请设置auhtor注释' }}</p>
        <br/>
        <p><strong>{{ isset($doc['description']) ? $doc['description'] : '' }}</strong></p><br/>

        <ul id="myTab" class="nav nav-tabs">
            <li class="active"><a href="#info" data-toggle="tab">接口信息</a></li>
            <li><a href="#test" data-toggle="tab">在线测试</a></li>
        </ul>
        <div class="tab-content">
            <!--info-->
            <div class="tab-pane fade in active" id="info">
                @if(isset($doc['header']) && is_array($doc['header']) && !empty($doc['header']))
            <h3>请求Headers</h3>
                <table class="table table-striped" >
                    <tr><th>名称</th><th>是否必须</th><th>默认值</th><th>说明</th></tr>
                    @foreach($doc['header'] as $header)
                <tr>
                        <td>{{ isset($header['name']) ? $header['name'] : "-" }}</td>
                        <td>@if($header['require']) 必填 @else 非必填  @endif</td>
                        <td>{{ isset($header['default']) ? $header['default'] : "-" }}</td>
                        <td>{{ isset($header['desc']) ? $header['desc'] : "-" }}</td>
                    </tr>
                    @endforeach
            </table>
                <br>
               @endif

            @if(isset($doc['param']) && is_array($doc['param']) && !empty($doc['param']))
             <h3>接口参数</h3>
                <table class="table table-striped" >
                    <tr><th>参数名字</th><th>类型</th><th>是否必须</th><th>默认值</th><th>其他</th><th>说明</th></tr>
                    @foreach($doc['param'] as $param)
                <tr>
                        <td>{{ isset($param['name']) ? $param['name'] : "-" }}</td>
                        <td>{{ isset($param['type']) ? $param['type'] : "-" }}</td>
                        <td>@if($param['require']) 必填 @else 非必填  @endif</td>
                        <td>{{ isset($param['default']) ? $param['default'] : "-" }}</td>
                        <td>{{ isset($param['other']) ? $param['other'] : "-" }}</td>
                        <td>{{ isset($param['desc']) ? $param['desc'] : "-" }}</td>
                    </tr>
                    @endforeach
            </table>
                <br>
                @endif
             @if(isset($doc['remark']))
            <h3>备注说明</h3>
                <div role="alert" class="alert alert-info">
                    {!! isset($doc['remark']) ? $doc['remark'] : '无' !!}
             </div>
                <br>
                @endif
          <div class="panel panel-primary" style="border-color: #00A881;">
                <div class="panel-heading" style="border-color: #00A881;background-color: #00A881">
                    <h3 class="panel-title">CURL代码(可快捷导入postman等工具)</h3>
                </div>
                <div class="panel-body">
                    <code>{{ $curl_code }}</code>
                </div>
            </div>
            <h3>返回结果</h3>
                <p><code id="json_text">{!! $return !!}</code></p>
            </div>
            <!--info-->
            <!--test-->
            <div class="tab-pane fade in" id="test">
                <br>
                <!--head-->
                <div class="panel panel-primary" style="border-color: #00A881">
                    <div class="panel-heading" style="border-color: #00A881;background-color: #00A881">
                        <h3 class="panel-title">接口参数</h3>
                    </div>
                    <div class="panel-body">
                        <form id="apiform" class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">接口地址</label>
                                <div class="col-sm-6">
                                    <input class="form-control" type="text" name="url" value='<?php echo isset($doc["url"]) ? Request::root().$doc["url"] : "请设置url注释"; ?>'>
                                </div>
                                <div class="col-sm-4"><button type="button" id="send" class="btn btn-success" data-loading-text="Loading..." autocomplete="off">发送测试</button></div>
                            </div>
                            @if(isset($doc['header']) && is_array($doc['header']) && !empty($doc['header']))
                        @foreach($doc['header'] as $header)
                        <div class="form-group">
                                <label class="col-sm-2 control-label"><span class="badge">header</span> {{ isset($header['name']) ? $header['name'] : '' }}</label>
                                <div class="col-sm-6">
                                    <input class="form-control" type="text" name="header[{{ $header['name'] }}]" value="">
                                </div>
                                <div class="col-sm-4"><label class="control-label text-warning"></label></div>
                              </div>
                               @endforeach
                      @endif
                      <div class="form-group">
                                <label class="col-sm-2 control-label">提交方式</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="method_type">
                                        <option value="GET" @if(isset($doc['method']) && strtoupper($doc['method']) == 'GET') selected @endif>GET</option>
                                        <option value="POST" @if(isset($doc['method']) && strtoupper($doc['method']) == 'POST') selected @endif>POST</option>
                                        <option value="PUT" @if(isset($doc['method']) && strtoupper($doc['method']) == 'PUT') selected @endif>PUT</option>
                                        <option value="DELETE" @if(isset($doc['method']) && strtoupper($doc['method']) == 'DELETE') selected @endif>DELETE</option>
                                    </select>
                                </div>
                                <div class="col-sm-4"></div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Cookie</label>
                                <div class="col-sm-6">
                                    <textarea class="form-control" type="text" name="cookie">{{ http_build_query($_COOKIE,'',';') }}</textarea>
                                </div>
                                <div class="col-sm-4"></div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">&nbsp;&nbsp;&nbsp;</label>
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#addParamModal">
                                        <span class="glyphicon glyphicon-plus"></span> 增加参数</button>
                                </div>
                            </div>
                            @if(isset($doc['param']) && is_array($doc['param']) && !empty($doc['param']))
                      @foreach($doc['param'] as $param)
                        <div class="form-group">
                                <label class="col-sm-2 control-label">{{ isset($param['name']) ? $param['name'] : '' }}</label>
                                <div class="col-sm-6">
                                    <input class="form-control" type="text" name="{{ isset($param['name']) ? $param['name'] : '' }}" value="">
                                </div>
                                <div class="col-sm-4"><label class="control-label text-warning"></label></div>
                            </div>
                            @endforeach
                      @endif
                    </form>
                    </div>
                </div>
                <!--head-->

                <div class="panel panel-primary" style="border-color: #00A881;">
                    <div class="panel-heading" style="border-color: #00A881;background-color: #00A881">
                        <h3 class="panel-title">返回结果</h3>
                    </div>
                    <div class="panel-body" id="span_result">
                        <div class="form-inline result_body">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addJosnTextmModal">自定义解析数据</button>
                            <label>缩进量:</label>
                            <select class="form-control" id="TabSize"  onchange="TabSizeChanged()">
                                <option value="1">1</option>
                                <option value="2" selected="true">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                            <input type="checkbox" id="QuoteKeys" onclick="QuoteKeysClicked()" checked="true"/> <label>引号</label>
                            <a href="javascript:void(0);" onclick="SelectAllClicked()">全选</a>
                            <a href="javascript:void(0);" onclick="ExpandAllClicked()">展开</a>
                            <a href="javascript:void(0);" onclick="CollapseAllClicked()">叠起</a>
                            <a href="javascript:void(0);" onclick="CollapseLevel(3)">2级</a>
                            <a href="javascript:void(0);" onclick="CollapseLevel(4)">3级</a>
                            <a href="javascript:void(0);" onclick="CollapseLevel(5)">4级</a>
                            <a href="javascript:void(0);" onclick="CollapseLevel(6)">5级</a>
                            <a href="javascript:void(0);" onclick="CollapseLevel(7)">6级</a>
                            <a href="javascript:void(0);" onclick="CollapseLevel(8)">7级</a>
                            <a href="javascript:void(0);" onclick="CollapseLevel(9)">8级</a>
                        </div>

                        <div id="Canvas" class="Canvas"></div>

                    </div>

                </div>

            </div>
            <!--test-->
        </div>


        <br/>
        <div role="alert" class="alert alert-info">
            <strong>提示：此文档是由系统自动生成，如发现错误或疑问请告知开发人员及时修改</strong>
        </div>
    </div>

    <p>&copy; {{ $copyright }} <p>
</div>
<!-- 模态框（Modal） -->
<div class="modal fade" id="addParamModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">增加参数</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">参数名</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="addparam" value="">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="addParam">提交</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<!-- 模态框（Modal） -->
<div class="modal fade" id="addJosnTextmModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">输入需要解析的json文本...</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">JSON文本</label>
                        <div class="col-sm-10">
                            <textarea class="form-control"  name="jsonText" style="width:450px;height: 200px;"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" id="addJson">解析</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
@stop

@section('footer')
<script type="text/javascript">
    $(function () {
        $('#addParamModal').on('show.bs.modal', function () {
            init();
        })
        $('#addParamModal').on('hide.bs.modal', function () {
            //关闭
        })
        //发送
        $("#send").click(function(){
            var $btn = $(this).button('loading');
            $.ajax({
                type: "POST",
                url: "{{ Request::root() }}/doc/debug",
                data: $("#apiform").serialize(),
                dataType:'json',
                success: function (data) {
                    window.json = data.result;
                    Process();
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

        // 添加自定义json
        $('#addJosnTextmModal').on('show.bs.modal', function () {
            init();
        })
        $('#addJosnTextmModal').on('hide.bs.modal', function () {
            //关闭
        })
        window.ImgCollapsed = "{{ Request::root() }}/apidoc/img/Collapsed.gif";
        window.ImgExpanded = "{{ Request::root() }}/apidoc/img/Expanded.gif";
    });

    function init(){
        $("#addParam").click(function(){
            var name = $('input[name="addparam"]').val();
            if(name.length > 0){
                var group = $("#apiform").find('.form-group').last().clone(true);
                $(group).find('.col-sm-2').text(name);
                $(group).find('.form-control').attr('name',name);
                $(group).find('.form-control').attr('value','');
                $(group).find('.text-warning').text('');
                $("#apiform").append(group);
            }
            $('#addParamModal').modal('hide');
        });

        $("#addJson").click(function(){
            window.json = $('textarea[name="jsonText"]').val();
            Process();
            $('#addJosnTextmModal').modal('hide');
        });
    }
</script>
@stop