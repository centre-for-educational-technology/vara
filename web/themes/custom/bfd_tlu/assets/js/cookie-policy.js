(function ($) {
  'use strict'
  const agreeToCookiePolicy = function () {
    try {
      window.localStorage.setItem('cookie-policy-agreement', 'true')
    } catch(e) {
      console.error('agreeToCookiePolicy', e)
    }
  }
  const hasAgreedToCookiePolicy = function() {
    try {
      return window.localStorage.getItem('cookie-policy-agreement') === 'true'
    } catch(e) {
      console.error('hasAgreedToCookiePolicy', e)
    }

    return false
  }
  const bypassCookiePolicy = function() {
    return window.location.href.includes('bypass-cookie-policy=true')
  }

  $(() => {
    if (bypassCookiePolicy()) return

    if (!hasAgreedToCookiePolicy()) {
      const selector = '#cookie-policy'
      const buttonSelector = '.btn.btn-primary'

      $(selector).find(buttonSelector).on('click', function() {
        $(selector).modal('hide')
        agreeToCookiePolicy()
      })

      $(selector).on('shown.bs.modal', function () {
        $(this).find(buttonSelector).trigger('focus')
      })

      $(selector).modal({
        backdrop: 'static',
        keyboard: false,
        focus: true,
        show: true
      })
    }
  })
})(jQuery)
