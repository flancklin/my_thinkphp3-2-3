

function initAlbum(showLable) {
    var Albums = new Object;
    Albums.tmpLable = showLable;
    Albums.tmpImg = '#tmp_album_img';
    Albums.tmpFolder = '#tmp_album_folder';
    Albums.model = 'select';
    Albums.store_id = 111;
    Albums.select = {
        allow: 0,
        folder_id: 0,
        img: [
//            {
//                id:1,
//                src:'www.baidu.com'
//            }
        ]
    };
    Albums.edit = {
        folder: [
//            {
//                id:1,
//                name:'nnn',
//                parent_id:2,
//                parent_name:'sds'
//            }
        ],
        img: [
//            {
//                id:2,
//                src:'www.baidu.com'
//            }
        ]
    };
    Albums.url = {
        get_img: '',
        get_folder: '',
        del_img: '',
        del_folder: '',
        move_img: '',
        move_folder: '',
    };
    Albums.page = 1;
    Albums.page_size = 20;

    Albums.clickImg = function (label) {
        var itemTmpObj =typeof(label) === "object" ? $(label) : $(this.tmpLable).find(label),
            id = itemTmpObj.data("id"),
            src = itemTmpObj.find("img").attr('src'),
            res = this.hasImg(id, 'img');
        if (!id) {
            console.log("点击图片没有找到图片的id");
            return false;
        }
        //有就删除
        if (typeof(res) === 'object') {
            var deleteItem = (this[this.model].img)[res['index']];
            (this[this.model].img).splice(res['index'], 1);
            this.cancelItemIcon(deleteItem, 'img');
        }
        //没有就添加
        if (res === false) {
            if (this.model === 'edit' || this.select.allow === 0 || (this.select.allow > (this.select.img).length)) {
                var addItem = {id: id, src: src};
                (this[this.model].img).push(addItem);
                this.showItemIcon(addItem, 'img');
            }
            //选择时，已达到极限。则删除最早的，把新的加进去
            if (this.select.allow === (this.select.img).length) {
                var deleteItem = (this[this.model].img).shift();
                this.cancelItemIcon(deleteItem, 'img');
                var addItem = {id: id, src: src};
                (this[this.model].img).push(addItem);
                this.showItemIcon(addItem, 'img');
            }
        }
    };
    Albums.clickFolder = function (label) {
        var itemTmpObj = typeof(label) === "object" ? $(label) : $(this.tmpLable).find(label),
            id = itemTmpObj.data("id"),
            name = itemTmpObj.data("name"),
            parent_id = itemTmpObj.data("parent_id"),
            parent_name = itemTmpObj.data("parent_name");

        if (!id) {
            console.log("点击文件没有找到文件的id");
            return false;
        }
        if (this.model === "edit") {
            //有就删除
            var res = this.hasImg(id, 'folder');
            if (typeof(res) === 'object') {
                var deleteItem = (this[this.model].folder)[ret['index']];
                (this[this.model].folder).splice(res['index'], 1);
                this.cancelItemIcon(deleteItem, 'folder');
            } else { //没有就添加
                var addItem = {id: id, name: name, parent_id: parent_id, parent_name: parent_name};
                (this[this.model].folder).push(addItem);
                this.showItemIcon(addItem, 'folder');
            }
        } else {
            if (id !== this.select.folder_id) {
                this.cancelItemIcon({id: this.select.folder_id}, 'folder');
                this.select.folder_id = id;
                this.showItemIcon({id: id}, 'folder');
            } else {
                //点击勾选的。则没必要操作
            }
        }
    };
    Albums.showItemIcon = function (objItem, type) {
        $(this.tmpLable).find(".item_" + type + "_" + objItem['id']).addClass("selected");
    };
    Albums.cancelItemIcon = function (objItem, type) {
        $(this.tmpLable).find(".item_" + type + "_" + objItem['id']).removeClass("selected");
    };
    Albums.hasImg = function (id, type) {
        var data = this[this.model][type];
        if (!data || data.length < 1) return false;
        for (var i in data) {
            var item = (this[this.model][type])[i];
            if (item['id'] == id) {
                return {index: i, item: item};//找到就返回索引和数据
            }
        }
        return false;
    };
    Albums.delImg = function () {
        var delData = this.edit.img;
        if (!delData || delData.length < 1) return this.showMsg('暂未选择图片');
        this.ajax("del_img", {}, function () {
            for (var i in this.edit.img) {
                this.delImg((this.edit.img)[i], 'img');
            }
            this.edit.img = [];
        })
    };
    Albums.delFolder = function () {
        var delData = this.edit.folder;
        if (!delData || delData.length < 1) return this.showMsg('暂未选择文件夹');
        this.ajax("del_folder", {}, function () {
            for (var i in this.edit.folder) {
                this.cancelItemIcon((this.edit.folder)[i], 'folder');
            }
            this.edit.folder = [];
        })
    };
    Albums.delItem = function (objItem, type) {
        $(this.tmpLable).find(".item_" + type + "_" + objItem['id']).remove();
    };
    Albums.moveImg = function (folder_id) {
        var moveData = this.edit.img;
        if (!moveData || moveData.length < 1) return this.showMsg('暂未选择图片');
        this.ajax("move_img", {folder_id: folder_id}, function () {
            //待续 有两种方案，1，直接从界面上去掉即可，2，重新从服务器拉新数据
            this.getImg();
        })
    };
    Albums.moveFolder = function (folder_id) {
        var moveData = this.edit.folder;
        if (!moveData || moveData.length < 1) return this.showMsg('暂未选择文件夹');
        this.ajax("move_folder", {folder_id: folder_id}, function () {
            this.getFolder();
        })
    };
//     Albums.folderList = [];
//     Albums.imgList = [];
    Albums.cacheData ={
        img :{
           'p_1':[
               {id:1,src:'http://img-cdn.7typ.cn/FmTkr3KJom_Q7CLGpMIJ2ggR2ZOG',name:'name1'},
               {id:2,src:'http://img-cdn.7typ.cn/FmTkr3KJom_Q7CLGpMIJ2ggR2ZOG',name:'name2'},
               {id:3,src:'http://img-cdn.7typ.cn/FmTkr3KJom_Q7CLGpMIJ2ggR2ZOG',name:'name3'},
               {id:4,src:'http://img-cdn.7typ.cn/FmTkr3KJom_Q7CLGpMIJ2ggR2ZOG',name:'name4'},
               {id:5,src:'http://img-cdn.7typ.cn/FmTkr3KJom_Q7CLGpMIJ2ggR2ZOG',name:'name5'},
               {id:6,src:'http://img-cdn.7typ.cn/FmTkr3KJom_Q7CLGpMIJ2ggR2ZOG',name:'name6'},
               {id:7,src:'http://img-cdn.7typ.cn/FmTkr3KJom_Q7CLGpMIJ2ggR2ZOG',name:'name7'},
           ],
           'p_2':[
               {id:1,src:'http://img-cdn.7typ.cn/FmTkr3KJom_Q7CLGpMIJ2ggR2ZOG',name:'name21'},
               {id:2,src:'http://img-cdn.7typ.cn/FmTkr3KJom_Q7CLGpMIJ2ggR2ZOG',name:'name22'},
           ]
    },
    folder: [
           {id:1,name:'aaa1',num:1,parent_id:0},
           {id:2,name:'aaa2',num:2,parent_id:1},
           {id:3,name:'aaa3',num:3,parent_id:0}
    ]
    };
    Albums.getImg = function (page) {
        if (page > 0) this.page = page;
        var pKey = 'p_' +this.page;
        if(!this.cacheData.img[pKey]){
           this.ajax("get_img",{},function (ret) {
               if(ret.data && ret.data.length > 0){
                   this.cacheData.img[pKey] = re.data;
               }
            })
        }
        this.showTmpImg();
    };
    Albums.showTmpImg = function () {
        var pKey = 'p_' +this.page,
            data = this.cacheData.img[pKey],
            html = '';
        if(data && data.length > 0){
            for(var i in data){
                var item = data[i];
                html +='<div class="item item_img_'+item['id']+'" data-id="'+item['id']+'"><img src="'+item['src']+'"><div class="name">'+item['name']+'</div><b></b><s></s></div>';
            }
        }
        $(this.tmpLable).find(".images .list").html(html);
        $(this.tmpLable).find(".images .item").on("click", function () {
            Albums.clickImg(this);
        })
        window.contextMenu(document.querySelectorAll(this.tmpLable +" .images .list .item"), [
            // 1
            {
                name:"第二个菜单1",
                id: "sec-menu-1",
                callback: function() {
                    alert("大天才");
                }
            }
        ]);
        return true;
    };
    Albums.getFolder = function () {
        if(!this.cacheData.folder){
            this.ajax("get_folder",{},function (ret) {
                if(ret.data && ret.data.length > 0){
                    this.cacheData.folder = re.data;
                }
            })
        }
        this.showTmpFolder();
    };
    Albums.showTmpFolder = function () {
        var data = this.cacheData.folder,
            html = '';
        if(data && data.length > 0){
            for(var i in data){
                var item = data[i];
                html += '<div class="item item_folder_'+item['id']+(item['parent_id'] > 0 ? ' sub ' : '')+'" data-id="'+item['id']+'" data-parent_id="'+item['parent_id']+'"><div class="name"><i></i><em>'+item['name']+'</em>('+item['num']+')</div><b></b><s></s></div>';
            }
        }
        $(this.tmpLable).find(".folder").html(html);
        $(this.tmpLable).find(".folder .item").on("click", function () {
            Albums.clickFolder(this);
        });

    };
    Albums.switchButton = function () {
        //编辑与选择的切换
        Albums.model = $(this).data("go_mode")
        $(this.tmpLable).find(".action").toggleClass("edit");
        $(this.tmpLable).find(".docker").toggleClass("edit");
        $(this).toggleClass("hide");
        $(this).siblings().toggleClass("hide");
        // $(this.tmpLable).find(".selected").removeClass("selected");
        this.showTmpFolder();//显示文件夹
        this.showTmpImg();//显示图片集
    };
    Albums.showMsg = function (msg) {
        alert(msg);
    };
    Albums.ajax = function (type, param, backFunc) {
        if (this.model !== "edit") return false;
        var url = this.url[type], async = true, method = "post", param = param;
        if (!url) return this.showMsg('操作类型错误');
        switch (type) {
            case 'move_img':
            case 'del_img':
                param['img'] = this.edit.img;
                break;
            case 'move_folder':
            case 'del_folder':
                param['folder'] = this.edit.folder;
                break;
            default:
                method = "GET";
                param = {page: this.page, page_size: this.page_size}
        }
        param['store_id'] = this.store_id;
        $.ajax({
            type: method,
            dataType: "json",
            url: url,
            async: async,
            data: param,
            error: function (XmlHttpRequest, textStatus, errorThrown) {

            },
            success: function (ret) {
                if (ret.msg) this.showmsg(ret.msg);
                if (ret.status) {
                    backFunc(ret);
                }
            },
            complete: function () {
            }
        });
        return true;
    }

    var T = $(Albums.tmpLable);
    T.html($("#tmp_album").html());
    T.find(".album .docker").css("height", window.document.body.offsetHeight*70/100-80);
    T.find(".album .folder").css("height", window.document.body.offsetHeight*70/100-80);
    T.find(".album .images .list").css("height", window.document.body.offsetHeight*70/100-80-30);

    T.find(".action .switch").on("click", function () {
        Albums.switchButton();
    });
    Albums.showTmpFolder();//显示文件夹
    Albums.showTmpImg();//显示图片集

    return Albums;
}