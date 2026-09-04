// Return the initialized instance for further use
function inttelInput(input) {
    try {
        return window.intlTelInput(input, {
            separateDialCode: true,
            preferredCountries: ["ae", "in", "sa", "qa", "om", "bh", "kw", "ma"],
            excludeCountries: ["AF", "CU", "KP", "IR", "LR", "LY", "MM", "SO", "SD", "SY", "UA"],
            initialCountry: "auto",
            showSelectedDialCode: true,
            geoIpLookup: function(callback) {
                $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            customPlaceholder: function() {
                return ""
            },
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/19.5.3/js/utils.js"
        });
    } catch (e) {
        console.log('Error: ' + e.message);
        return null;
    }
}

function getCountryCodeAndNumber(Mobile) {
    try {
        var countryCode = Mobile.getSelectedCountryData().dialCode;
        var number = Mobile.getNumber().replace('+' + countryCode, '');
        return countryCode + number;
    } catch (e) {
        console.log('Error: ' + e.message);
    }
}


function showToast(icon, title, duration = 5000, onTimerEnd) {
  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: duration,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.onmouseenter = Swal.stopTimer;
      toast.onmouseleave = Swal.resumeTimer;
    },
    didClose: () => {
      if (typeof onTimerEnd === 'function') {
        onTimerEnd();
      }
    }
  });
  
  Toast.fire({
    icon: icon,
    title: title
  });
}
