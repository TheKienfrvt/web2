// Hàm hiển thị thông báo
function showAlert(type, message) {
  // Tạo alert element
  let alertClass, iconClass;

  switch (type) {
    case 'success':
      alertClass = 'alert-success';
      iconClass = 'fa-check-circle';
      break;
    case 'error':
      alertClass = 'alert-danger';
      iconClass = 'fa-exclamation-triangle';
      break;
      case 'warning':
      alertClass = 'alert-warning';
      iconClass = 'fa-exclamation-circle';
      break;
  }

  const alertHtml = `
                      <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                      <i class="fas ${iconClass} me-2"></i>
                      ${message}
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>
                    `;

  // Thêm alert vào body
  $('body').append(alertHtml);

  console.log(`showAlert: ${type} | ${message}`);

  // Tự động ẩn sau 5 giây
  setTimeout(function () {
    $('.alert').alert('close');
  }, 5000);
}