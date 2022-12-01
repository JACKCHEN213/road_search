(function () {
    let Button = window.Button = function () {
        this.startBtn = document.getElementById('start_btn');
        this.restartBtn = document.getElementById('restart_btn');
        this.nextBtn = document.getElementById('next_btn');
        this.runningBtn = document.getElementById('running_btn');
    };

    Button.prototype.disableBtn = function (btn) {
        btn.className = 'btn disable';
        btn.disabled = true;
    }

    Button.prototype.enableBtn = function (btn, class_name) {
        btn.className = class_name;
        btn.disabled = false;
    }

    Button.prototype.setStyle = function (mode = 'disable_all') {
        switch (mode) {
            case "disable_all":
                this.setStyle('disable_start');
                this.setStyle('disable_restart');
                this.setStyle('disable_next');
                this.setStyle('disable_running');
                break;
            case "disable_start":
                this.disableBtn(this.startBtn);
                break;
            case "disable_restart":
                this.disableBtn(this.restartBtn);
                break;
            case "disable_next":
                this.disableBtn(this.nextBtn);
                break;
            case "disable_running":
                this.disableBtn(this.runningBtn);
                break;
            case "enable_all":
                this.setStyle('enable_start');
                this.setStyle('enable_restart');
                this.setStyle('enable_next');
                this.setStyle('enable_running');
                break;
            case "enable_start":
                this.enableBtn(this.startBtn, 'btn primary');
                break;
            case "enable_restart":
                this.enableBtn(this.restartBtn, 'btn success');
                break;
            case "enable_next":
                this.enableBtn(this.nextBtn, 'btn danger');
                break;
            case "enable_running":
                this.enableBtn(this.runningBtn, 'btn warning');
                break;
        }
    }
})();
