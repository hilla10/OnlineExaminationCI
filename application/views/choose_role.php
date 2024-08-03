<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Choose Role</title>
</head>
<body>
    <h1>Select Your Role</h1>
    <?php $csrf = $this->security->get_csrf_hash(); ?>
    <form action="<?= base_url('auth/register_with_role') ?>" method="post">
        <input type="hidden" name="email" value="<?= $this->session->userdata('temp_user')['email'] ?>">
        <input type="hidden" name="username" value="<?= $this->session->userdata('temp_user')['username'] ?>">
        <input type="hidden" name="profile_picture" value="<?= $this->session->userdata('temp_user')['profile_picture'] ?>">

        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $csrf ?>">

        <label for="role">Choose your role:</label>
        <select name="role" id="role" onchange="handleRoleChange()">
            <option value="2">Student</option>
            <option value="3">Lecturer</option>
        </select>

        <button type="submit">Submit</button>
    </form>

    <script>
        function handleRoleChange() {
            var role = document.getElementById('role').value;
            if (role == '3') { // Lecturer
                document.getElementById('role_form').action = '<?= base_url('lecturer/save') ?>';
                document.getElementById('role_form').method = 'post'; // Ensure the form method is POST
                document.getElementById('role_form').style.display = 'block'; // Show form
            } else {
                document.getElementById('role_form').style.display = 'none'; // Hide form
            }
        }
    </script>
</body>
</html>