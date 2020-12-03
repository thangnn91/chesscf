$(document).ready(function () {
    $('form.form-ajax').keypress('input', function () {
        $('.m-alert').addClass('m--hide');
    });
    $('form.form-ajax').ajaxForm({
        beforeSubmit: function (formData, jqForm, option) {
            jqForm.loading(
                {
                    onStart: function (loading) {
                        loading.overlay.slideDown(400);
                    }
                }
            );
            jqForm.find('input').attr('disable', 'disable');
            return true;
        },
        success: function (responseText, statusText, xhr, $form) {
            $form.find('input').attr('disable', '');
            if (!responseText.statusCode || responseText.statusCode < 0) {
                $form.loading('stop');
                if (responseText.isPopup) {
                    app.swalNotification(undefined, responseText.message, 'error');
                } else {
                    app.toastr.error(responseText.message);
                }
            } else {
                if (responseText.modal) {
                    app.toastr.success(responseText.message);
                    $form.loading('stop');
                    $('.modal').modal('hide');
                    if (responseText.reload) {
                        app.swalNotification(window.location.href, responseText.message, 'success');
                    }
                } else if (responseText.redirect) {
                    $form.loading('stop');
                    app.toastr.success(responseText.message);
                    setTimeout(function () {
                        window.location.href = responseText.redirect;
                    }, 900);
                } else if (responseText.html) {
                    app.toastr.success(responseText.message);
                    $form.loading('stop');
                    var element = document.getElementById('result_html_form');
                    if (typeof (element) != 'undefined' && element != null) {
                        $('#result_html_form').html(responseText.html);
                    } else {
                        $form.html(responseText.html);
                    }

                } else if (responseText.onPage) {
                    $form.loading('stop');
                    app.swalNotification(undefined, responseText.message, 'success');
                } else {
                    app.swalNotification(window.location.href, responseText.message, 'success');
                    $form.loading('stop');
                }
            }
        },
        error: function (responseText, statusText, xhr, $form) {
            if (responseText.status === 422) {
                $('.alert_detail').html('<ul>');
                $.each(responseText.responseJSON.errors, function (key, value) {
                    $('.alert_detail').append('<li>' + value + '</li>');
                    $('.alert_detail').append('</ul>');
                    $form.find('.m-alert').removeClass('m--hide').show();
                });
                $form.loading('stop');
                app.toastr.error('Input invalid');
                return;
            }
            $form.loading('stop');
            app.toastr.error('Server to busy');
        }
    });
});

var app = {
    toastr: toastr,
    ajax: function (url, method, data, type, callback) {
        $.ajax({
            type: method,
            method: method,
            data: data,
            url: url,
            dataType: 'JSON',
            success: function (responseText) {
                callback(responseText);
            },
            error: function (jqXHR, textStatus, errorThrow) {
                callback({ statusCode: 0, data: [], message: 'Server to busy' });
            }
        });
    },
    // swalNotification: function(url, message, type){
    //     swal({
    //         title: "Are you sure?",
    //         text: "You won't be able to revert this!",
    //         type: "warning",
    //         showCancelButton: !0,
    //         confirmButtonText: "Yes, delete it!",
    //         cancelButtonText: "No, cancel!",
    //         reverseButtons: !0
    //     }).then(function(e) {
    //         if(e.value) window.location.href = url;
    //     });
    // },
    swalNotification: function (url, message, type) {
        swal({
            title: message,
            text: '',
            type: type ? type : 'succes',
            showCancelButton: false,
            confirmButtonText: "Ok"
        }).then(function (e) {
            if (url != undefined) {
                if (e.value) window.location.href = url;
            }
        });
    },
    ajaxLoading: function (url, method, data, type, object, callback) {
        $.ajax({
            type: method,
            method: method,
            data: data,
            url: url,
            dataType: 'JSON',
            beforeSend: function () {
                if (object) {
                    $(object).loading(
                        {
                            onStart: function (loading) {
                                loading.overlay.slideDown(400);
                            }
                        }
                    );
                }
            },
            success: function (responseText) {
                if (object) {
                    $(object).loading('stop');
                }
                callback(responseText);
            },
            error: function (jqXHR, textStatus, errorThrow) {
                if (object) {
                    $(object).loading('stop');
                }
                callback({ statusCode: 0, data: [], message: 'Server to busy' });
            }
        });
    },

    swal: function (message, url) {
        swal({
            title: "Are you sure?",
            text: message,
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: !0
        }).then(function (e) {
            if (e.value) window.location.href = url
        });
    }
};

Utils = new function () {
    function rootUrl() {
        var rootUrl = window.location.origin + '/';
        if (rootUrl.indexOf('localhost') !== -1)
            rootUrl = rootUrl + "bestmoneyv2/public/";
        return rootUrl;
    };
    this.UrlRoot = rootUrl();
};

interval = {
    //to keep a reference to all the intervals
    intervals: {},

    //create another interval
    make: function (fun, delay) {
        //see explanation after the code
        var newInterval = setInterval.apply(
            window,
            [fun, delay].concat([].slice.call(arguments, 2))
        );

        this.intervals[newInterval] = true;

        return newInterval;
    },

    //clear a single interval
    clear: function (id) {
        return clearInterval(this.intervals[id]);
    },

    //clear all intervals
    clearAll: function () {
        var all = Object.keys(this.intervals), len = all.length;

        while (len-- > 0) {
            clearInterval(all.shift());
        }
    }
};

function calSessionTimeout(lifetime) {
    interval.clearAll();
    timeout = parseInt(lifetime) * 60;
    expired = interval.make(function () {
        if (--timeout == 0) {
            clearInterval(expired);
            swal({
                title: "Thông báo",
                text: "Phiên đăng nhập đã kết thúc",
                type: "warning",
                confirmButtonText: "Quay lại trang chủ",
            }).then(function (e) {
                window.location.href = Utils.UrlRoot;
            });
        }
    }, 1000);
}
function formatMoney(argValue) {
    if (argValue == 0)
        return '0';
    var comma = (1 / 2 + '').charAt(1);
    var digit = ',';
    if (comma == '.') {
        digit = '.';
    }

    var sSign = "";
    if (argValue < 0) {
        sSign = "-";
        argValue = -argValue;
    }

    var sTemp = "" + argValue;
    var index = sTemp.indexOf(comma);
    var digitExt = "";
    if (index != -1) {
        digitExt = sTemp.substring(index + 1);
        sTemp = sTemp.substring(0, index);
    }

    var sReturn = "";
    while (sTemp.length > 3) {
        sReturn = digit + sTemp.substring(sTemp.length - 3) + sReturn;
        sTemp = sTemp.substring(0, sTemp.length - 3);
    }
    sReturn = sSign + sTemp + sReturn;
    if (digitExt.length > 0) {
        sReturn += comma + digitExt;
    }
    return sReturn;
}
function formatDate(date, notTime) {
    var year = date.getFullYear(),
        month = date.getMonth() + 1, // months are zero indexed
        day = date.getDate();
    if (notTime) {
        return day + "/" + month + "/" + year
    }
    var hour = date.getHours(),
        minute = date.getMinutes(),
        second = date.getSeconds(),
        hourFormatted = hour % 12 || 12, // hour returned in 24 hour format
        minuteFormatted = minute < 10 ? "0" + minute : minute,
        morning = hour < 12 ? "am" : "pm";
    return day + "/" + month + "/" + year + " " + hourFormatted + ":" + minuteFormatted + morning;
}
function btn_loading(id) {
    $('#' + id).addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
}

function btn_unloading(id) {
    $('#' + id).removeClass('m-loader m-loader--right m-loader--light').removeAttr('disabled');
}

function notify(msg, tp = 'info') {
    $.notify(msg, {
        type: tp,
        animate: {
            enter: 'animated bounceInDown',
            exit: 'animated bounceOutUp'
        }
    });
}

// Get data modal
function LoadDataModal(url, type = '') {
    $.get(url, function (data) {
        $('#common_modal .modal-title').html(data['modal-title']);
        $('#common_modal .modal-body').html(data['modal-body']);
        $('#common_modal .modal-footer').html(data['modal-footer']);
        $('#common_modal .modal-dialog').attr('class', 'modal-dialog ' + type);
        $('#common_modal').modal({ backdrop: 'static', keyboard: false, show: true });
    });
}
$("#common_modal").on("hidden.bs.modal", function () {
    $('#common_modal .modal-content').removeAttr('style');
});

function ImageResizeByCanvas(base64, width, height) {
    return new Promise(function (resolve, reject) {
        var image = new Image();
        image.src = base64;
        var canvas = document.createElement("canvas");
        var ctx = canvas.getContext("2d");
        var maxW = width;
        var maxH = height;
        image.onload = function () {
            var iw = image.width;
            var ih = image.height;
            var sx = 0;
            var sy = 0;
            if (iw > ih) {
                sx = (iw - ih) / 2;
                iw = ih;
            } else {
                sy = (ih - iw) / 2;
                ih = iw;
            }
            var scale = Math.min((maxW / iw), (maxH / ih));
            var iwScaled = iw * scale;
            var ihScaled = ih * scale;
            canvas.width = iwScaled; // target width
            canvas.height = ihScaled; // target height
            ctx.drawImage(image,
                sx, sy, iw, ih,
                0, 0, iwScaled, ihScaled
            );
            resolve(canvas.toDataURL());
        };
    });
}
function convertIntObj(obj) {
    const res = {}
    for (const key in obj) {
        res[key] = {};
        for (const prop in obj[key]) {
            const parsed = parseInt(obj[key][prop], 10);
            res[key][prop] = isNaN(parsed) ? obj[key][prop] : parsed;
        }
    }
    return res;
}

function SlideToogle(dt_slide, dt_toogle, id_next) {
    let slideId, slideNext, slideActive, slide, slideNextHeight;
    var index = 1;

    slideId = $('#' + dt_slide);
    slideActive = slideId.children('.div_active');
    slide = slideId.children('.slide');
    if (slide.length <= 0) {
        slide = slideId.children('.div_slide');
    }
    if (id_next != undefined && id_next != "") {
        var index_next = $("#" + id_next).index();
        index = Math.abs(slideActive.index() - index_next);
    }
    if (dt_toogle === 'next') {
        slideNext = slideActive.index() + index;
        slideNext = slideNext > slide.length ? slide : slideNext;
    } else if (dt_toogle === 'prev') {
        slideNext = slideActive.index() - index;
        slideNext = slideNext < 0 ? 0 : slideNext;
    }
    var slideNextElm = slide.eq(slideNext);
    slideNextHeight = slideNextElm.outerHeight(true);//.height() + parseInt(slideNextElm.css('margin-bottom')) + parseInt(slideNextElm.css('margin-top'));
    if (slideNextHeight == 0) {
        var parent = slideId.parents('.div_hidden');
        if (parent.length <= 0) {
            parent = slideId.parents('.div_next');
        }

        parent.attr("style", "display:block");
        slideHeight = slide.outerHeight(true); //height() + parseInt(slide.css('margin-bottom')) + parseInt(slide.css('margin-top'));
        parent.removeAttr("style");
    }
    $.each(slide, function (k, v) {
        var idxCrr = $(v).index();
        if (idxCrr < slideNext && !$(v).hasClass("div_prev"))
            $(v).removeClass("div_next div_active").addClass("div_prev");
        else if (idxCrr === slideNext)
            slideNextElm.removeClass("div_next div_prev").addClass("div_active");
        else if (!$(v).hasClass("div_next"))
            $(v).removeClass("div_prev div_active").addClass("div_next");
    });
    slideId.height(slideNextHeight);
    return false;
}

function checkPhoneFormat(phone_number) {
    phone_number = phone_number.trim();
    if (phone_number.charAt(0) == '8' && phone_number.charAt(1) == '4')
        phone_number = '0' + phone_number.substr(2, phone_number.length);
    //regex 10 số
    var regex = /(0)((9)+([0-4]|[6-9])+([0-9]{7})\b)|(0)((8)+([1-6]|[8|9])+([0-9]{7})\b)|(0)((7)+([0]|[6-9])+([0-9]{7})\b)|(0)((5)+([6|8|9])+([0-9]{7})\b)|(0)((3)+([2-9])+([0-9]{7})\b)/;
    return (phone_number.length == 10 && regex.test(phone_number));
}

function checkEmailFormat(email) {
    email = email.trim();
    regex = /^[a-z][a-z0-9_\.]{5,32}@[a-z0-9]{2,}(\.[a-z0-9]{2,4}){1,2}$/;
    return regex.test(email);
}

function getTransactionStatus(status) {
    switch (status) {
        case 20:
        case 30:
            return '<span class="m-badge m-badge--brand m-badge--wide">Khởi tạo</span>';
        case 21:
        case 22:
        case 23:
        case 33:
            return '<span class="m-badge  m-badge--info m-badge--wide">Đang xử lý</span>';
        case 24:
        case 32:
            return '<span class="m-badge  m-badge--success m-badge--wide">Thành công</span>';
        case 25:
        case 34:
            return '<span class="m-badge  m-badge--danger m-badge--wide">Thất bại</span>';
        case 4:
        case 27:
        case 36:
            return '<span class="m-badge  m-badge--danger m-badge--wide">Hủy</span>';
        case 31:
            return '<span class="m-badge  m-badge--warning m-badge--wide">Chờ duyệt</span>';
        case 35:
            return '<span class="m-badge  m-badge--success m-badge--wide">Đã duyệt</span>';

        case 40:
            return '<span class="m-badge  m-badge--success m-badge--wide">Thanh toán(out)</span>';
        case 41:
            return '<span class="m-badge  m-badge--success m-badge--wide">Thanh toán(in)</span>';
        default:
            return '<span class="m-badge  m-badge--metal m-badge--wide">Khác</span>';
    }
}
function getTransactionType(type) {
    switch (type) {
        case 2001:
            return 'Nạp';
        case 3001:
            return 'Rút';
        case 4001:
            return 'Thanh toán';
        default:
            return 'Không xác định';
    }
}

function getLotteryName(code) {
    switch (code) {
        case 'vl645':
            return 'Vietlot 645';
        case 'xsmb':
            return 'Xổ số miền bắc';
        case 'dmb':
            return 'Đặc biệt';
        case 'lmb':
            return 'Lô tô';
        case 'lmb2':
            return 'Lô tô xiên 2';
        case 'lmb3':
            return 'Lô tô xiên 3';
        case 'lmb4':
            return 'Lô tô xiên 4';
        default:
            return 'Không xác định';
    }
}
function validatePhoneNumber(phone) {
    var phoneRegex = /((09|03|07|08|05)+([0-9]{8})\b)/g;
    if (!phone || !phoneRegex.test(phone))
        return false;
    return true;
}

function numberChangeAnimation(from, to, id) {
    var odd = '';
    if ((from * 1000) % 1000 > 0)
        odd = from.toString().substring(from.toString().length - 2, from.toString().length);
    from = parseInt(from);
    to = parseInt(to);
    if (from !== 0 || to !== 0) {
        $({ someValue: from }).animate({ someValue: to }, {
            duration: 1000,
            easing: 'swing', // can be anything
            step: function () { // called on every step
                // Update the element's text with rounded-up value:
                $('#' + id).text(commaSeparateNumber(Math.round(this.someValue)) + odd);
            }
        });
    }
}

async function randomNumberAnimation(res, output, duration = 5000) {
    // var output = $('#' + id).length === 0 ? $('.' + id) : $('#' + id);
    var started = new Date().getTime();
    return await new Promise(resolve => {
        animationTimer = setInterval(function () {
            if (output.text().trim() === res || new Date().getTime() - started > duration) {
                clearInterval(animationTimer);
                output.text(res);
                if (res % 2 === 0) output.addClass('even');
                else output.addClass('odd');
                return true;
            } else {
                output.text(
                    '' +
                    Math.floor(Math.random() * 10) +
                    Math.floor(Math.random() * 10) +
                    Math.floor(Math.random() * 10) +
                    Math.floor(Math.random() * 10)
                );
            }
        }, 100);
    });
}

function commaSeparateNumber(val) {
    while (/(\d+)(\d{3})/.test(val.toString())) {
        val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    }
    return val;
}

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ')
            c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0)
            return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function showLoading() {
    hideLoading();
    var html = '<div id="loader_wrapper"><section class="wrapper dark">' +
        '<div class="spinner">' +
        '<i></i>' +
        '<i></i>' +
        '<i></i>' +
        '<i></i>' +
        '<i></i>' +
        '<i></i>' +
        '<i></i>' +
        '</div>' +
        '</section></div>';
    $('body').append(html);
}

function hideLoading() {
    $('#loader_wrapper').remove();
}

function getUrlParam(param) {
    return new URL(window.location.href).searchParams.get(param);
}
function change_alias(alias) {
    var str = alias;
    str = str.toLowerCase();
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    str = str.replace(/đ/g, "d");
    str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'|\"|\&|\#|\[|\]|~|\$|_|`|-|{|}|\||\\/g, " ");
    str = str.replace(/ + /g, " ");
    str = str.trim();
    return str;
}
window.business = {
    save_product: function () {

    },
}
