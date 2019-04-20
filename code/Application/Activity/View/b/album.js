var ALBUM = {
    mode:'select',//select/edit
	now_obj:'',
	main_tmp:'',
    select:{
		//选择模式下，选择的数据不会直接传输给后台，而是返回数据集给前端，前端展示，待前端其他数据一并完善后才传后台
		//故不需要id与url进行剥离，剥离反而不利于【选择】和【取消选择】的切换操作
        folder_id: 0,//当前选择的文件夹
		images:[],//当前已选择的图片集[{id:url},{id:url}]
		num:0,
        allow: 0,//最多可选多少个。0-不限制。1-最多选1个。2-最多选两个
        page: 1,
        page_size: 28,
    },
    edit:{
        folder:{
            arr:[],//[{fid:fname},{fid:fname}]
            //ids:',',//,id1,id2,id3,
        },
        images:{
            arr:[],//[{iid:iurl},{iid:iurl}]
            //ids:',',////,id1,id2,id3,
        },
    },
    checkEsist(id, needDelete){
        if(this.mode == 'select'){
			for (var i in this.select.images){
					if(this.select.images[i]['id'] === id){
						if(needDelete) {
							var flag = this.select.images[i];
							(this.select.images).splice(i,1);
							return flag;
						};
						return true;
					}
				}
        }else{
            if(this.now_obj == 'folder'){
				for (var i in this.edit.folder.arr){
					if(this.edit.folder.arr[i]['id'] === id){
						if(needDelete) {
							var flag = this.edit.folder.arr[i];
							(this.edit.folder.arr).splice(i,1);
							return flag;
						};
						return true;
					}
				}	
			}else{
				for (var i in this.edit.images.arr){
                if(this.edit.images.arr[i]['id'] === id){
                    if(needDelete) {
						var flag = this.edit.images.arr[i];
                        (this.edit.images.arr).splice(i,1);
						return flag;
                    };
                    return true;
                }
            }
			}
        }
		return false;
    },
	freshFooter:function(){
		var T_select = '';
		for(var i in this.select.images){
			var item = this.select.images[i];
			T_select += '<div class="item self_'+item['id']+'" data-id="'+item['id']+'">'
					 +'<span onclick="albumDelShowImg(this)" class="del">X</span>'
					 +'<img src="'+item['url']+'">'
					 +'</div>';
		}
		this.main_tmp && this.main_tmp.find(".s_show").html(T_select);
	},
    url: {
        folder_list: 'url_folder_list',
        img_list: 'url_img_list'
    },
	tmp: {
        folder_list: '',
        img_list: ''
    },
    showMsg(msg){
        alert(msg);
    },
    createHtml(tmp, data){
        return _.template(tmp, data);
    },
    ajax: function (ajaxType, url, param, async) {
        // $.ajax({
        //     type: type,
        //     url: url,
        //     async:async,
        //     data: param,
        //     success: function (ret) {
        //         if(ret.status == true){
        //             dealOk(ret.data);
        //         }else{
        //             this.showMsg(ret.msg);
        //         }
        //     },
        //     error: function () {
        //         this.showMsg(ret.msg);
        //     }
        // });
        var ret = {};
        switch (url) {
            case this.url.folder_list:
                ret = {
                    "status": 1,
                    "total": "1",
                    "data": [{
                        "id": "108",
                        "storeId": "1000",
                        "name": "\u7cfb\u7edf\u56fe\u6807",
                        "parent_id": "0",
                        "picNum": "112",
                        "_child":[{
                            "id": "281",
                            "storeId": "707020",
                            "name": "\u4e8c\u7ea7\u6587\u4ef6",
                            "parent_id": "0",
                            "picNum": "0"
                        }, {
                            "id": "282",
                            "storeId": "707020",
                            "name": "\u4e00\u7ea7\u6587\u4ef6",
                            "parent_id": "0",
                            "picNum": "0"
                        }],
                    }, {
                        "id": "281",
                        "storeId": "707020",
                        "name": "\u4e8c\u7ea7\u6587\u4ef6",
                        "parent_id": "0",
                        "picNum": "0",
                        "_child":[{
                            "id": "281",
                            "storeId": "707020",
                            "name": "\u4e8c\u7ea7\u6587\u4ef6",
                            "parent_id": "0",
                            "picNum": "0"
                        }, {
                            "id": "282",
                            "storeId": "707020",
                            "name": "\u4e00\u7ea7\u6587\u4ef6",
                            "parent_id": "0",
                            "picNum": "0"
                        }],
                    }, {
                        "id": "282",
                        "storeId": "707020",
                        "name": "\u4e00\u7ea7\u6587\u4ef6",
                        "parent_id": "0",
                        "picNum": "0",
                        "_child":[],
                    }],
                    "msg": ""
                };
                this.showFolder(ret);
                break;
            case this.url.img_list:
                ret = {
                    "status": 1,
                    "data": [{
                        "id": "5118",
                        "store_id": "708010",
                        "cate_id": "0",
                        "file": "http:\/\/img-cdn.7typ.cn\/FmQz-nCpD8GHUn8YE_Pg_K8sGz_H",
                        "name": "1547955470"
                    }, {
                        "id": "5117",
                        "store_id": "708010",
                        "cate_id": "0",
                        "file": "http:\/\/img-cdn.7typ.cn\/FrNBe7OqO7ZfZNAN1hd-TFQtPeGw",
                        "name": "1547955447"
                    }, {
                        "id": "4963",
                        "store_id": "708010",
                        "cate_id": "0",
                        "file": "http:\/\/img-cdn.7typ.cn\/Fj55OuFjzBsjkIsEKdmlwCTyQYyn",
                        "name": "1547783573"
                    }, {
                        "id": "4962",
                        "store_id": "708010",
                        "cate_id": "0",
                        "file": "http:\/\/img-cdn.7typ.cn\/FtqzL8as7MLKey23fftiyo2zXW_D",
                        "name": "1547783517"
                    }, {
                        "id": "4961",
                        "store_id": "708010",
                        "cate_id": "0",
                        "file": "http:\/\/img-cdn.7typ.cn\/FtMUauRHgYf3sO60B8gMwG8DQChI",
                        "name": "1547782949"
                    }, {
                        "id": "4960",
                        "store_id": "708010",
                        "cate_id": "0",
                        "file": "http:\/\/img-cdn.7typ.cn\/FsAgtlNmV_1ptfjSYFHtWhpIsG56",
                        "name": "1547782836"
                    }, {
                        "id": "4959",
                        "store_id": "708010",
                        "cate_id": "0",
                        "file": "http:\/\/img-cdn.7typ.cn\/lrSuAOt741gW-8hgZhNx_qV2WTZo",
                        "name": "1547781691"
                    }, {
                        "id": "4958",
                        "store_id": "708010",
                        "cate_id": "0",
                        "file": "http:\/\/img-cdn.7typ.cn\/Fjyv2PqMCNAuJ7WzkBwd1cB4F0-W",
                        "name": "1547781493"
                    }, {
                        "id": "4957",
                        "store_id": "708010",
                        "cate_id": "0",
                        "file": "http:\/\/img-cdn.7typ.cn\/FrIf26FCovgtUfGzgCjFLaSM0-3w",
                        "name": "1547779207"
                    }, {
                        "id": "4956",
                        "store_id": "708010",
                        "cate_id": "0",
                        "file": "http:\/\/img-cdn.7typ.cn\/FvgW7CqAuB1xjIufryYR3aI2wVm-",
                        "name": "1547779186"
                    }, {
                        "id": "4931",
                        "store_id": "708010",
                        "cate_id": "0",
                        "file": "http:\/\/img-cdn.7typ.cn\/Fnyns_p4OMZZAjp-HCFMMlVFPsVB",
                        "name": "1547627749"
                    }, {
                        "id": "4927",
                        "store_id": "708010",
                        "cate_id": "0",
                        "file": "http:\/\/img-cdn.7typ.cn\/FmRSYUENSPo46NtiyZMOHUcHxPeL",
                        "name": "1547619951"
                    }, {
                        "id": "4926",
                        "store_id": "708010",
                        "cate_id": "0",
                        "file": "http:\/\/img-cdn.7typ.cn\/Fp3b-EfCILUAh4vznVqcJthEpiLp",
                        "name": "1547619926"
                    }],
                    "page": "    "
                };
                this.showImg(ret);
                break;
            default:
        }
    },
    getListFolder: function () {
        var param = {folder_id: this.folder_id};
        this.ajax('GET', this.url.folder_list, param, true)
    },
    getImgList(){
        var param = {
            page: this.page,
            page_size: this.page_size,
            folder_id: this.folder_id,
            name: this.search_name,
            select_ids: this.select_ids,
        };
        this.ajax('GET', this.url.img_list, param, true)
    },
    showFolder(ret){
        var tmpHtml = this.createHtml($(this.tmp.folder_list).html(), ret);
        $(this.handle.folder_list).html(tmpHtml);
    },
    showImg(ret){
        var tmpHtml = this.createHtml($(this.tmp.img_list).html(), ret);
        $(this.handle.img_list).html(tmpHtml);
    }
}
