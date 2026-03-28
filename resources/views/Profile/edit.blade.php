@extends('layout.main-template')

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
  <style>
    /* Global styles */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      font-size: 16px;
      line-height: 1.5;
      background-color: #f5f5f5;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Header styles */
    header {
      background-color: #8c1aff;
      color: #fff;
      padding: 10px;
      text-align: center;
    }

    nav ul {
      list-style-type: none;
      display: flex;
      justify-content: center;
      margin: 0;
      padding: 0;
    }

    nav li {
      margin: 0 10px;
    }

    nav a {
      color: #fff;
      text-decoration: none;
      transition: color 0.3s;
    }

    nav a:hover {
      color: #ddd;
    }

    /* User profile styles */
    .user-profile {
      display: flex;
      align-items: center;
      margin-top: 20px;
      flex-wrap: wrap;
    }

    .user-info {
      flex-grow: 1;
      min-width: 200px;
    }

    .user-name {
      margin-top: 0;
      font-size: 1.2rem;
    }

    .email {
      font-size: 1rem;
    }

    .email-visibility {
      color: #666;
      font-size: 0.9rem;
    }

    .profile-pic {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      margin-right: 20px;
    }

    .file-upload {
      display: inline-block;
      background-color: #8c1aff;
      color: #fff;
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    .file-upload input[type="file"] {
      display: none;
    }

    /* Additional info styles */
    .additional-info {
      margin-top: 20px;
    }

    /* Footer styles */
    footer {
      text-align: center;
      color: #666;
      padding: 10px;
      font-size: 0.9rem;
    }

    /* Responsive styles */
    @media (max-width: 600px) {
      .user-profile {
        flex-direction: column;
        align-items: flex-start;
      }

      .profile-pic {
        margin-bottom: 10px;
      }

      .file-upload {
        width: 100%;
        text-align: center;
      }
    }
  </style>
</head>
<body>
    @section('content')
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <div class="container">
    <div class="row flex-lg-nowrap">

    
      <div class="col">
        <div class="row">
          <div class="col mb-3">
            <div class="card">
              <div class="card-body">
                <div class="e-profile">
                  <div class="row">
                    <div class="col-12 col-sm-auto mb-3">
                      <div class="mx-auto" style="width: 140px;">
                        <div class="d-flex justify-content-center align-items-center rounded" style="height: 140px; background-color: rgb(233, 236, 239);">
                          <span style="color: rgb(166, 168, 170); font: bold 8pt Arial;">140x140</span>
                        </div>
                      </div>
                    </div>
                    <div class="col d-flex flex-column flex-sm-row justify-content-between mb-3">
                      <div class="text-center text-sm-left mb-2 mb-sm-0">
                        <h4 class="pt-sm-2 pb-1 mb-0 text-nowrap">John Smith</h4>
                        <p class="mb-0">@johnny.s</p>
                        <div class="text-muted"><small>Last seen 2 hours ago</small></div>
                        <div class="mt-2">
                          <button class="btn btn-primary" type="button">
                            <i class="fa fa-fw fa-camera"></i>
                            <span>Change Photo</span>
                          </button>
                        </div>
                      </div>
                      <div class="text-center text-sm-right">
                        <span class="badge badge-secondary">administrator</span>
                        <div class="text-muted"><small>Joined 09 Dec 2017</small></div>
                      </div>
                    </div>
                  </div>
                  <ul class="nav nav-tabs">
                    <li class="nav-item"><a href="" class="active nav-link">Settings</a></li>
                  </ul>
                  <div class="tab-content pt-3">
                    <div class="tab-pane active">
                      <form class="form" novalidate="">
                        <div class="row">
                          <div class="col">
                            <div class="row">
                              <div class="col">
                                <div class="form-group">
                                  <label>Full Name</label>
                                  <input class="form-control" type="text" name="name" placeholder="John Smith" value="John Smith">
                                </div>
                              </div>
                              <div class="col">
                                <div class="form-group">
                                  <label>Username</label>
                                  <input class="form-control" type="text" name="username" placeholder="johnny.s" value="johnny.s">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col">
                                <div class="form-group">
                                  <label>Email</label>
                                  <input class="form-control" type="text" placeholder="user@example.com">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col mb-3">
                                <div class="form-group">
                                  <label>About</label>
                                  <textarea class="form-control" rows="5" placeholder="My Bio"></textarea>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12 col-sm-6 mb-3">
                            <div class="mb-2"><b>Change Password</b></div>
                            <div class="row">
                              <div class="col">
                                <div class="form-group">
                                  <label>Current Password</label>
                                  <input class="form-control" type="password" placeholder="••••••">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col">
                                <div class="form-group">
                                  <label>New Password</label>
                                  <input class="form-control" type="password" placeholder="••••••">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col">
                                <div class="form-group">
                                  <label>Confirm <span class="d-none d-xl-inline">Password</span></label>
                                  <input class="form-control" type="password" placeholder="••••••"></div>
                              </div>
                            </div>
                          </div>
                         
                        </div>
                        <div class="row">
                          <div class="col d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">Save Changes</button>
                          </div>
                        </div>
                      </form>
    
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
    
          <div class="col-12 col-md-3 mb-3">
            <div class="card mb-3">
              <div class="card-body">
                <div class="px-xl-3">
                  <button type="button" onclick="location.href='{{route('login')}}'" class="btn btn-block btn-secondary">
                    <i class="fa fa-sign-out"></i>
                    <span>Logout</span>
                  </button>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <h6 class="card-title font-weight-bold">Support</h6>
                <p class="card-text">Get fast, free help from our friendly assistants.</p>
                <button type="button" class="btn btn-primary">Contact Us</button>
              </div>
            </div>
          </div>
        </div>
    
      </div>
    </div>
    </div>
  @endsection
</body>
</html>
