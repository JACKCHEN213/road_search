(function () {
    let HTTP = window.HTTP = function (url = null) {
        if (url !== null) {
            this.url = url;
        }
        this.url = 'http://192.168.12.96:1026/road_search';
    };

    HTTP.prototype.getInitData = function (status, callback) {
        axios.post(this.url, {
            status,
        }).then((response) => {
            if (response.status !== 200) {
                throw new Error(response.statusText);
            }
            let {map, open_list, close_list, src_point, dst_point, roads} = response.data;
            map = JSON.parse(map);
            src_point = JSON.parse(src_point);
            dst_point = JSON.parse(dst_point);
            open_list = JSON.parse(open_list);
            close_list = JSON.parse(close_list);
            roads = JSON.parse(roads);
            callback(map, src_point, dst_point, open_list, close_list, roads);
        }).catch((error) => {
            console.error(error);
        });
    }
})();