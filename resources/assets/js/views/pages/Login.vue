<template>
  <div class="app flex-row align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <center><img width="180px" src="/images/logo.png"/></center><br/>
          <div class="card-group mb-0">
            <div class="card p-4">
              <form v-model="valid" ref="form" lazy-validation @submit.prevent="login">
                <div class="card-body">
                  <h3>Login</h3>
                  <p class="text-muted">Sign In to your account</p>
                  <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
                  <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
                  <div class="input-group mb-3">
                    <span class="input-group-addon"><i class="icon-user"></i></span>
                    <input type="email" v-model="credential.email" :rules="credential.emailRules" required class="form-control" placeholder="Email">
                    <small class="error__control" v-if="error.email">{{error.email[0]}}</small>
                  </div>
                  <div class="input-group mb-4">
                    <span class="input-group-addon"><i class="icon-lock"></i></span>
                    <input type="password" v-model="credential.password" :rules="credential.passwordRules" required class="form-control" placeholder="Password">
                    <small class="error__control" v-if="error.password">{{error.password[0]}}</small>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <button type="submit" class="btn btn-primary px-4">Login</button>
                    </div>
                    <div class="col-6 text-right">
                      <button type="button" class="btn btn-link px-0">Forgot password?</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
              <div class="card-body text-center">
                <div>
                  <h2>Sign up</h2>
                  <p>Here is sign up form.</p>
                  <button type="button" class="btn btn-primary active mt-3">Register Now!</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">
    import Flash from '../../../services/flash'
    import axios from 'axios'
    import Vue from 'vue'

    export default {
        data() {
            return {
                valid: true,
                credential: {
                    email: '',
                    password: '',
                    emailRules: [
                      (v) => !!v || 'E-mail is required',
                      (v) => /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
                    ],
                    passwordRules: [
                      (v) => !!v || 'password is required',
                      (v) => v && v.length <= 32 || 'Code must be less than 32 characters'
                    ]
                },
                flash: Flash.state,
                error: Flash.state,
                isProcessing: false
            }
        },
        mounted: function () {
            console.log(this.$http);
        },
        ready() {
          this.credential.email    = credential.email;
          this.credential.password = credential.password;
          this.login();
        },
        methods: {
            login() {
              // if (this.$refs.form.validate()) {
                  this.isProcessing = true
                  this.error = {}
                  axios.post('/login', this.credential)
                      .then((res) => {
                      // if(res.data.success) {
                          Flash.setSuccess('Congratulations! You have now successfully registered.')
                          localStorage.setItem('userId', res['data']['userId'])
                          localStorage.setItem('token', res['data']['token'])
                          window.location.href="/#/dashboard"
                          // this.$router.push('/admin')
                      // }else{
                      //     Flash.setError('Error while trying to login.')
                      //     // this.$router.push('/register')
                      // }
                      this.isProcessing = false
                  })
                  .catch((err) => {
                      if(err.response.status === 422) {
                          this.error = err.response.data
                      }
                      Flash.setError('Error while trying to login.')
                      this.isProcessing = false
                  })
              }
            // }
        }
    }
</script>
