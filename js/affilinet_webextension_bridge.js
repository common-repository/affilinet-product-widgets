var openInWidgetsPageInBrowserExtension =   function(widgetId) {
    var event;
    if (widgetId !== null) {
        event = new CustomEvent('affilinet-browser-extension-open-widgets', { detail: {widgetId : widgetId.toString()}});
    }
    else {
        event = new CustomEvent('affilinet-browser-extension-open-widgets');
    }
    document.dispatchEvent(event);
}