(function () {
    let Draw = window.Draw = function () {
        this.map_node = document.getElementById('map');
    };

    Draw.prototype.drawMap = function (map) {
        let map_node = document.getElementById('map');
        map_node.innerHTML = '';
        for (let i = 0; i < map.length; i++) {
            let row_node = document.createElement('div');
            row_node.classList.add('row');
            row_node.id = 'row' + i;
            for (let j = 0; j < map[i].length; j++) {
                let node = document.createElement('div');
                node.classList.add('node');
                if (map[i][j]['block']) {
                    node.classList.add('block_node');
                }
                node.id = 'node' + i + j;
                row_node.appendChild(node);
                node.innerHTML = map[i][j]['price'];
            }
            map_node.appendChild(row_node);
        }
    };

    Draw.prototype.drawSrcAndDst = function (src_point, dst_point) {
        let src_node = document.getElementById('node' + src_point['x'] + src_point['y']);
        let dst_node = document.getElementById('node' + dst_point['x'] + dst_point['y']);
        src_node.classList.add('src_node');
        dst_node.classList.add('dst_node');
    };

    Draw.prototype.inPoints = function (point, exclude_points) {
        for (let exclude_point of exclude_points) {
            if (point['x'] == exclude_point['x'] && point['y'] == exclude_point['y']) {
                return true;
            }
        }
        return false;
    }

    Draw.prototype.drawRoads = function (roads, exclude_points) {
        for (let road of roads) {
            if (this.inPoints(road, exclude_points)) {
                continue;
            }
            let node = document.getElementById('node' + road['x'] + road['y']);
            node.classList.add('road_node');
        }
    };

    Draw.prototype.drawOpenList = function (open_list) {
        for (let point of open_list) {
            let node = document.getElementById('node' + point['x'] + point['y']);
            node.classList.add('road_node');
        }
    }
})();