// ایجاد یک فایل به نام script.js و اضافه کردن کدهای زیر
function analyzeWebsite() {
    var websiteUrl = document.getElementById('websiteUrl').value;

    if (websiteUrl.trim() === '') {
        alert('Please enter a valid website URL.');
        return;
    }

    // ارسال درخواست به سرور یا اجرای توابع سئو
    // اینجا به عنوان مثال یک پیغام ساده نمایش داده می‌شود:
    var resultContainer = document.getElementById('analysisResult');
    resultContainer.innerHTML = '<p>Website analysis for ' + websiteUrl + ' is complete. Your SEO score is 85 out of 100.</p>';
}
