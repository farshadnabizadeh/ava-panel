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
            render(route, 'registerComponent.html')
            break
        default:
            console.log('Not Found')
            break;
    }
}


const render = (origin, pathname, componentName) => {
    $('#root').load(origin + pathname + '/Components/' + componentName)
}