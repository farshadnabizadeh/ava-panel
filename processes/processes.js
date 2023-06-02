jQuery(document).ready(function () {
    route()
})

const route = () => {
    var origin = window.location.origin;
    var route = window.location.href;
    var pathname = window.location.pathname;
    var component = (window.location.href.split('?route='))[1]
    switch (component) {
        case 'login':
            loginComponent(pathname, 'loginComponent.html')
            break
        default:
            console.log('Not Found')
            break;
    }
}
const loginComponent = (pathname, componentName) => {
    $.get(`${pathname}Components/${componentName}`, function (data) {
        $('#root').html(data);
    }, 'html');
}
