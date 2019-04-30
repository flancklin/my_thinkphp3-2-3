var ALBUM = {
    page: 1,
    page_size: 28,
    folder_id: 0,
    search_name: '',
    select_allow: 0,//最多可选多少个。0-不限制。1-最多选1个。2-最多选两个
    selected: [],//已选择的个数
    selected_length:0,
    ui_mode:'select',
    initImg(){
        this.selected = [];
        this.selected_length = 0;
    },
    checkImgEsist(img_id, needDelete){
        for (var i in this.selected){
            if(this.selected[i]['id'] === img_id){
                if(needDelete) {
                    (this.selected).splice(i,1);
                    this.selected_length -= 1;
                };
                return true;
            }
        }
        return false;
    },
    addImg(param){
        if(!this.checkImgEsist(param['id'])){
            (this.selected).push(param);
            this.selected_length += 1;
        }
    },
    subImg(img_id){
        this.checkImgEsist(img_id, true);
    },
    shiftImg(){
        var deleteImg = (this.selected).shift();
        if(deleteImg){
            this.selected_length -= 1;
        }
        return deleteImg;
    },
    url: {
        folder_list: 'url_folder_list',
        img_list: 'url_img_list'
    },
    handle: {
        switch_button:'',
        folder_list: '',
        img_list: '',
        folder:'',
        img:'',
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
