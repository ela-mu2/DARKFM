
$(document).ready(function() {
    $('#addScheduleForm').on('submit', function(e) {
        e.preventDefault(); // 1. 死死按住表单，阻止原生刷新提交
        
        const form = this;
        const formData = new FormData(form);
        // 确保把日期的 name (air_date) 映射到后端需要的 'date' 字段上
        formData.append('date', $('#air_date').val());

        fetch('api/api_check_conflict.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // 2. 只有后端明确返回 success（无冲突），才允许真正提交写入数据库
                form.submit(); 
            } else if (data.status === 'conflict') {
                // 3. 完美的暗黑风冲突提示
                Swal.fire({
                    icon: 'error',
                    title: '排班冲突',
                    text: data.message,
                    background: '#1a1a1a',
                    color: '#fff',
                    confirmButtonColor: '#008080'
                });
            } else {
                // 4. 拦截其他意外错误（比如系统、数据库报错）
                Swal.fire({
                    icon: 'warning',
                    title: '系统提示',
                    text: data.message || '检测到未知错误。',
                    background: '#1a1a1a',
                    color: '#fff',
                    confirmButtonColor: '#64748b'
                });
            }
        })
        .catch(err => {
            console.error("Fetch Error:", err);
        });
    });
});