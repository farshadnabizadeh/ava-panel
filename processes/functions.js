const dashboardProcess = () => {
    $('#dashboard-btn').css({
        'color': '#4154f1',
        'background': '#f6f9ff',
        'cursor': 'pointer',
    })
    $('#dashboard-btn>i').css({
        'color': '#4154f1',
    })
    renderMainSection('dashboard.html')
}

const buyVPNProcess = () => {
    $('#buy-vpn').css({
        'color': '#4154f1',
        'background': '#f6f9ff',
        'cursor': 'pointer',
    })
    $('#buy-vpn>i').css({
        'color': '#4154f1',
    })
    renderMainSection('buyvpn.html')
}

const renderMainSection = (DOM) => {
    $('#mainSection_panelComponent').load(getURL() + 'Components/Sections/' + DOM);
}