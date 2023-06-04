jQuery(document).ready(function () {
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
const signupRequest  = () => {
    $("#signup-request").click(function() {
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
    $('#createAccount').click(function(){
        $.post(getURL() + 'HTTPResponses/processes/public/api/user/createaccount',
        {
            username: $('#username').val(),
            email: $('#email').val(),
            password : $('#password').val(),
        },
        function(data){
            console.log("Data: " + data);
        });
    })
}




