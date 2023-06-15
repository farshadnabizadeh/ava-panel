jQuery(document).ready(function () {
    resetAllSessions()
    route()
})

const route = () => {
    var origin = window.location.origin;
    var route = window.location.href;
    var pathname = window.location.pathname;
    var component = (window.location.href.split('?route='))[1]
    switch (component) {
        case 'signin':
            render(origin, pathname, 'loginComponent.html')
            break
        case 'signup':
            render(origin, pathname, 'registerComponent.html')
            break
        default:
            render(origin, pathname, 'loginComponent.html')
            break;
    }
}
const resetAllSessions = () => {
    $.post(getURL() + 'HTTPResponses/processes/public/api/reset/session', function (response) { })
}
const signupRequest = () => {
    $("#signup-request").click(function () {
        window.location.href = '?route=signup'
    });
}
const signinRequest = () => {
    $("#signin-request").click(function () {
        window.location.href = '?route=signin'
    });
}
const getURL = () => {
    var origin = window.location.origin;
    var pathname = window.location.pathname;
    return origin + pathname;
}
const render = (origin, pathname, componentName) => {
    $('#root').load(origin + pathname + '/Components/' + componentName)
}
const createAccount = () => {
    $('#createAccount').click(function () {
        $.post(getURL() + 'HTTPResponses/processes/public/api/user/createaccount',
            {
                username: $('#username').val(),
                password: $('#password').val(),
                email: $('#email').val(),
                acceptTerms: $('#acceptTerms').is(":checked") ? 'enabled' : 'disabled',
            },
            function (response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Ok',
                        text: response.data,
                    }).then(() => {
                        window.location.href = getURL() + '?route=signin'
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.data,
                    })
                }
            });
    })
}