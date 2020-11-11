// fungsi dialog confirm
function confirm_bpm(nomor) {
    var form = 'form-bpm'.concat(nomor);
    new duDialog('Anda Yakin ?', 'ini tidak dapat dikembalikan', duDialog.OK_CANCEL, {
        okText: 'OK',
        cancelText: 'Tidak',
        callbacks: {
            okClick: function () {
                document.getElementById(form).submit();
                return true;
            },
            cancelClick: function () {
                this.hide();
                return false;
            },
        }
    });
}
function confirm_cakahim(no_ckh) {
    var form = 'form-kahim'.concat(no_ckh);
    new duDialog('Anda Yakin ?', 'ini tidak dapat dikembalikan', duDialog.OK_CANCEL, {
        okText: 'OK',
        cancelText: 'Tidak',
        callbacks: {
            okClick: function () {
                document.getElementById(form).submit();
                return true;
            },
            cancelClick: function () {
                this.hide();
                return false;
            },
        }
    });
}

function confirm_capresma(nomorcw) {
    var formcw = 'form-presma'.concat(nomorcw);
    new duDialog('Anda Yakin ?', 'ini tidak dapat dikembalikan', duDialog.OK_CANCEL, {
        okText: 'OK',
        cancelText: 'Tidak',
        callbacks: {
            okClick: function () {
                document.getElementById(formcw).submit();
                return true;

            },
            cancelClick: function () {
                this.hide();
                return false;
            },
        }
    });
}

function confirm_choice() {
    new duDialog('Anda Yakin ?', 'ini tidak dapat dikembalikan', duDialog.OK_CANCEL, {
        okText: 'OK',
        callbacks: {
            okClick: function () {
                window.location.href = "/mahasiswa/upload-ktm";
            },
        }
    });
}

function verified_choice() {
    new duDialog('Anda Yakin ?', 'ini tidak dapat dikembalikan', duDialog.OK_CANCEL, {
        okText: 'OK',
        callbacks: {
            okClick: function () {
                document.getElementById('form-upload-ktm').submit();
                return true;
            },
        }
    });
}

function review() {
    new duDialog('Anda Yakin untuk mengulang pemilihan?', 'ini tidak dapat dikembalikan', duDialog.OK_CANCEL, {
        okText: 'OK',
        callbacks: {
            okClick: function () {
                window.location.href = "/mahasiswa/review";
            },
        }
    });
}
