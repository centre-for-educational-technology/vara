(function ($) {
  'use strict'

  const isSameUrl = function(a, b) {
    return a.origin + a.pathname === b.origin + b.pathname
  }

  $(() => {
    const mainMenu = $('nav.navigation.menu--main')

    if (mainMenu.find('li.nav-item > a.active.is-active').length === 0) {
      const currentUrl = window.location

      mainMenu.find('li.nav-item > a').each((index, element) => {
        const url = new window.URL(element.href)

        if (isSameUrl(currentUrl, url)) {
          $(element).addClass('active is-active')
        }
      })
    }
  })
})(jQuery)
