(function () {
    let Road = window.Road = function () {
        this.roads_node = document.getElementById('roads');
    };

    Road.prototype.drawRoads = function (roads) {
        if (!roads.length) {
            this.roads_node.innerHTML = '';
            return;
        }
        if (typeof roads[0] == 'string' && roads[0] === 'not_find') {
            this.roads_node.innerHTML = '未找到路径';
            return;
        }
        for (let i = 0; i < roads.length; i++) {
            let road_node = document.createElement('div');
            road_node.className = 'road';
            road_node.innerHTML = '(' + roads[i]['x'] + ', ' + roads[i]['y'] + ')';
            this.roads_node.appendChild(road_node);
            if (i < roads.length - 1) {
                let road_link_node = document.createElement('div');
                road_link_node.className = 'road_link';
                road_link_node.innerHTML = ' => ';
                this.roads_node.appendChild(road_link_node);
            }
        }
    }
})();
