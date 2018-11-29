function alertMsg(msg,bigtitle,type,time) {

    if(time){
        window.setTimeout(function(){
            $('[data-dismiss="alert"]').alert('close');
        },2000);
    }
    if(!type){
        type='danger';
    }
    return '<div class="alert alert-'+type+' alert-dismissible fade in" role="alert">\n' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>\n' + (bigtitle?"<strong>"+bigtitle+"</strong>":"")+ msg + '</div>';

}

/**
 * 上传相关
 * **/
var _count = 0;
var _expando = new Date() - 0;

var upload = function (options) {

    var opt = options || {};

    var id = options.id = options.id || _expando + _count;

    options = $.extend(true, {}, upload.defaults, opt);

    var o = upload.get(id);
    if(o){
        return o;
    }
    return upload.list[id] = new upload.create(options);
};

upload.create = function (options) {
    var that = this;

    //$.extend(this, new Plupload());

    var defaultSetting = {
        browse_button : options.id,
        runtimes : 'html5,flash,silverlight,html4',
        url: '/system/auth_upload',
        /*multipart_params: {
            token: $('body').data('uptoken')
        },*/
        filters : {
            max_file_size : '1MB',
            mime_types: [
                {title : "application andriod files", extensions : "png,jpg,jpeg,gif,apk,ipa"}
            ]
        },
        // Flash settings
        flash_swf_url : '/assets/plupload/Moxie.swf',
        multi_selection:false,
        // Silverlight settings
        silverlight_xap_url : '/assets/plupload/Moxie.xap'
    };

    options = $.extend(true, defaultSetting, options);
    this.Uploader = new plupload.Uploader(options);
    this.Uploader.init();
    return this;

};
upload.getToken=function () {
    var that = this;
    $.ajax({
        type : "get",
        //async:true,
        url : "/system/jstoken",
        dataType : "json",
        success : function(res){
            var uptoken = res.data.uptoken;
            var domain = res.data.domain;
            $("body").data("uptoken",uptoken);
            $("body").data("domain",domain);
        },
        error:function(){

        }
    });
};
/**
 * 根据 ID 获取某对话框 API
 * @param    {String}    对话框 ID
 * @return   {Object}    对话框 API (实例)
 */
upload.get = function (id) {
    return id === undefined
        ? upload.list
        : upload.list[id];
};
upload.list = {};
