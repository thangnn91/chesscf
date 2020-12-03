// the game itself
var game;

var gameOptions = {

    // slices (prizes) placed in the wheel
    slices: 8,

    // prize names, starting from 12 o'clock going clockwise
    // wheel rotation duration, in milliseconds
    rotationTime: 3000
}

// once the window loads...
window.onload = function () {

    // game configuration object
    var gameConfig = {

        // render type
        type: Phaser.CANVAS,

        parent: 'wheel_game',

        // game width, in pixels
        width: 550,

        // game height, in pixels
        height: 550,

        // game background color
        backgroundColor: 0x880044,

        // scenes used by the game
        scene: [playGame]
    };

    // game constructor
    game = new Phaser.Game(gameConfig);

    // pure javascript to give focus to the page/frame and scale the game
    window.focus()
    resize();
    window.addEventListener("resize", resize, false);
}

// PlayGame scene
class playGame extends Phaser.Scene {

    // constructor
    constructor() {
        super("PlayGame");
    }

    // method to be executed when the scene preloads
    preload() {

        // loading assets
        this.load.image("wheel", Utils.UrlRoot + "minigame/wheel/wheel.png");
        this.load.image("pin", Utils.UrlRoot + "minigame/wheel/pin.png");
    }

    // method to be executed once the scene has been created
    create() {

        // adding the wheel in the middle of the canvas
        this.wheel = this.add.sprite(game.config.width / 2, game.config.height / 2, "wheel");

        // adding the pin in the middle of the canvas
        this.pin = this.add.sprite(game.config.width / 2, game.config.height / 2 - 13, "pin").setInteractive();

        // adding the text field
        this.prizeText = this.add.text(game.config.width / 2, game.config.height - 20, "Spin the wheel", {
            font: "bold 26px Arial",
            align: "center",
            color: "white"
        });

        // center the text
        this.prizeText.setOrigin(0.5);

        // the game has just started = we can spin the wheel
        this.canSpin = true;

        // waiting for your input, then calling "spinWheel" function
        // this.input.on("pointerdown", this.spinWheel, this);
        this.pin.on("pointerdown", this.spinWheel, this);
    }

    // function to spin the wheel
    spinWheel() {

        if (!_extra && (_rb + _gb < 10000)) {
            swal({
                title: "Số dư không đủ",
                text: "Bạn có muốn nạp tiền ngay?",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Nạp ngay",
                cancelButtonText: "Hủy"
            }).then(function (e) {
                e.value && (window.location.href = Utils.UrlRoot + 'user/cashin')
            });
            return;
        }

        // can we spin the wheel?
        if (this.canSpin) {
            this.prizeText.setText("");

            // the wheel will spin round from 2 to 4 times. This is just coreography
            var rounds = Phaser.Math.Between(2, 4);

            // then will rotate by a random number from 0 to 360 degrees. This is the actual spin
            var degrees = 1;
            var desc;

            var cur_gb = _gb;
            var cur_rb = _rb;
            if(!_extra) {
                if (cur_gb >= 10) {
                    numberChangeAnimation(cur_gb, cur_gb - 10, 'game_balance');
                    cur_gb -= 10;
                } else {
                    numberChangeAnimation(cur_rb, cur_rb - 10, 'real_balance');
                }
            }

            showLoading();
            $.ajax({
                type: "POST",
                url: Utils.UrlRoot + 'user/playWheelGame',
                async: false, 
                success: function (data) {
                    if (data.ResponseCode >= 0) {
                        degrees = data.Position;
                        desc = data.Description;
                        _rb = data.rb;
                        _gb = data.gb;
                        if(_extra) _gb += 10;
                        _extra = data.Bonus;

                    } else if (data.ResponseCode === -1) {
                        swal({
                            title: "Số dư không đủ",
                            text: "Bạn có muốn nạp tiền ngay?",
                            type: "warning",
                            showCancelButton: !0,
                            confirmButtonText: "Nạp ngay",
                            cancelButtonText: "Hủy"
                        }).then(function (e) {
                            e.value && (window.location.href = Utils.UrlRoot + 'user/cashin')
                        });

                    } else {
                        swal("Thất bại!", data.Description, "warning");
                    }
                    hideLoading();
                }
            }).fail(function () {
                swal("Thất bại!", 'Hệ thống đang bận, vui lòng thử lại sau!', "warning");
                hideLoading();
            });
            // resetting text field
            
            // before the wheel ends spinning, we already know the prize according to "degrees" rotation and the number of slices
//            var prize = gameOptions.slices - 1 - Math.floor(degrees / (360 / gameOptions.slices));

            // now the wheel cannot spin because it's already spinning
            this.canSpin = false;

            // animation tweeen for the spin: duration 3s, will rotate by (360 * rounds + degrees) degrees
            // the quadratic easing will simulate friction
            this.tweens.add({

                // adding the wheel to tween targets
                targets: [this.wheel],

                // angle destination
                angle: 360 * rounds + degrees,

                // tween duration
                duration: gameOptions.rotationTime,

                // tween easing
                ease: "Cubic.easeOut",

                // callback scope
                callbackScope: this,

                // function to be executed once the tween has been completed
                onComplete: function (tween) {

                    // displaying prize text
                    this.prizeText.setText(desc);

                    // Header balance
                    numberChangeAnimation(cur_gb, _gb, 'game_balance');

                    // player can spin again
                    this.canSpin = true;

                }
            });
        }
    }
}

// pure javascript to scale the game
function resize() {
    var canvas = document.querySelector("canvas");
    var parent = document.getElementById('wheel_game');
    var windowWidth = parent.offsetWidth;
    var windowHeight = parent.offsetHeight;
    var windowRatio = windowWidth / windowHeight;
    var gameRatio = game.config.width / game.config.height;
    if (windowRatio < gameRatio) {
        canvas.style.width = windowWidth + "px";
        canvas.style.height = (windowWidth / gameRatio) + "px";
    } else {
        canvas.style.width = (windowHeight * gameRatio) + "px";
        canvas.style.height = windowHeight + "px";
    }
}
