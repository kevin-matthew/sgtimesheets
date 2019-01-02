<?php
include ('../run/header.php');

?>

<section>
	<ul>
		<li>There will be a $_GET variable that represents the userid 
			in the database.</li>
		<li>Checks if either the user on the page is the owner of
			the account they are trying to edit or if the user is an
			admin. If neither, then the user will be redirected to
			the home page.</li>
		<li>This page has the ability to update user information like
			first name, middle name, last name, {user name}, {email},
			{employee ID}, {start date}, and password.</li>
		
	</ul>
</section>

<section>
	<p>Anything with a {something} means that I haven't decided on
		whether or not a regular user should have the ability to edit
		that information. I say that a regular user can edit his user name
		and email, but not his employee ID nor start date. It would take an
		admin to edit a user's employee ID and start date. But the final
		decision on this is up to Kevin. - Brett Lange
	</p>
</section>

</body>
</html>
<?php
include ('../run/footer.php');
