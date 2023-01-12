<x-mail::message>
  <div style="padding: 20px, 30px">
    <h1>
      Account Details
    </h1>
    <table>
      <tr>
        <th style="padding: 5px 0px; text-align: left">Name</th>
        <td style="padding: 5px 0px">: {{$user['name']}}</td>
      </tr>
      <tr>
        <th style="padding: 5px 0px; text-align: left">Email</th>
        <td style="padding: 5px 0px">: {{$user['email']}}</td>
      </tr>
      <tr>
        <th style="padding: 5px 0px; text-align: left">Password</th>
        <td style="padding: 5px 0px">: {{$user['password']}}</td>
      </tr>
      <tr>
        <th style="padding: 5px 0px; text-align: left">Role</th>
        <td style="padding: 5px 0px">: {{$user['role']}}</td>
      </tr>
    </table>
    <div style="margin-top: 20px; padding-left: 3px">
      <small>
        <strong>Note: </strong>Do not share your account information with other people
      </small>
    </div>
  </div>
</x-mail::message>