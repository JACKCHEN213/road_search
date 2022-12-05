(function () {
    let Point = window.Point = function () {
        this.src_x_node = document.getElementById('src_x');
        this.src_y_node = document.getElementById('src_y');
        this.dst_x_node = document.getElementById('dst_x');
        this.dst_y_node = document.getElementById('dst_y');
        this.nodes = [this.src_x_node, this.src_y_node, this.dst_x_node, this.dst_y_node];
    };

    Point.prototype.setPoints = function (src_point, dst_point) {
        this.src_x_node.value = src_point['x'];
        this.src_y_node.value = src_point['y'];
        this.dst_x_node.value = dst_point['x'];
        this.dst_y_node.value = dst_point['y'];
    };

    Point.prototype.setStyle = function (mode = 'disable') {
        for (let node of this.nodes) {
            if (mode === 'disable') {
                node.classList.add('disable');
                node.disabled = true;
            } else {
                node.classList.remove('disable');
                node.disabled = false;
            }
        }
    }
})();