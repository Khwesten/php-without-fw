var alreadyUserWithThisEmail = false;

$(document).ready(function () {
    $('#form-register').on('submit', function () {
        event.preventDefault();

        var form = $('#form-register');

        var email = $('#form-register #email').val();
        var confirmEmail = $('#form-register #confirmEmail').val();

        var password = $('#form-register #password').val();
        var confirmPassword = $('#form-register #confirmPassword').val();

        var name = $('#form-register #name').val();

        if (alreadyUserWithThisEmail) {
            showError("This email already in use.")
        } else if (email != confirmEmail) {
            showError("The filled email, doesn't equal to confirm email.")
        } else if (password != confirmPassword) {
            showError("The filled password, doesn't equal to confirm password.")
        } else if (!name) {
            showError("The name is required.")
        } else {
            $.ajax({
                method: "POST",
                // url: '/index.php?type=controller&class=user&method=register',
                url: '/api/user/register',
                data: form.serialize(),
            })
                .done(function (msg) {
                    showSuccess(msg.message);
                })
                .fail(function (msg) {
                    var jsonMsg = JSON.parse(msg.responseText);
                    showError(jsonMsg.message);
                });
        }
    });

    $('#form-register #email').keyup(function (event) {
        var email = this.value;

        if (email.indexOf("@") != -1) {
            $.ajax({
                method: "GET",
                // url: '/index.php?type=controller&class=user&method=getByEmail',
                url: '/api/user/email',
                data: {email: email},
            })
                .done(function (msg) {
                    alreadyUserWithThisEmail = true;
                    $("#message-email").html("<small style='color: red'>Esse email está em uso.</small>")
                })
                .fail(function (msg) {
                    var jsonMsg = JSON.parse(msg.responseText);

                    if (jsonMsg.code == 404) {
                        alreadyUserWithThisEmail = false;
                        $("#message-email").html("<small style='color: forestgreen'>Esse email não está em uso.</small>")
                    } else if (jsonMsg.code == 500) {
                        showError(jsonMsg.message);
                    }
                });
        }
    });

    $('#form-register #password').keyup(function (event) {
        var password = this.value;

        const twoTinyCharacter = /[a-z]{2,}/g;
        const twoDigits = /\d{2,}/g;
        const twoEspecialCharacter = /\W{2,}/g;
        const oneCapitalCharacter = /[A-Z]{1,}/g;

        var scoreOfGodPass = 0;

        scoreOfGodPass += containsRegex(password, twoTinyCharacter) ? 1 : 0;
        scoreOfGodPass += containsRegex(password, twoDigits) ? 1 : 0;
        scoreOfGodPass += containsRegex(password, twoEspecialCharacter) ? 1 : 0;
        scoreOfGodPass += containsRegex(password, oneCapitalCharacter) ? 1 : 0;

        var colors = ['red', 'orange', 'yellow', 'green'];

        $('#password-score').css("width", (scoreOfGodPass * 25) + "px");
        $('#password-score').css("background-color", colors[scoreOfGodPass - 1]);
    });

});

/**
 * Check if contains regex in string
 *
 * @param string
 * @param regex
 * @returns {boolean}
 */
function containsRegex(string, regex) {
    var has = false;
    while ((m = regex.exec(string)) !== null) {
        if (m.index === regex.lastIndex) {
            regex.lastIndex++;
        }

        m.forEach(function (match, groupIndex) {
            has = true;
        });
    }
    return has;
}

/**
 * Show a error message on form div
 *
 * @param message
 */
function showError(message) {
    window.scrollTo(0, 0);
    $('#alert').html('<div class="alert alert-danger" role="alert">' + message + '</div>');
    $('#alert').show();

    setTimeout(function () {
        $('#alert').hide();
    }, 6000);
}

/**
 * Show a error message on form div
 *
 * @param message
 */
function showSuccess(message) {
    window.scrollTo(0, 0);
    $('#alert').html('<div class="alert alert-success" role="alert">' + message + '</div>');
    $('#alert').show();

    setTimeout(function () {
        $('#alert').hide();
    }, 6000);
}