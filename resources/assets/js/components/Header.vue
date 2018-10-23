<template>
  <span id="inspire">
    <div id="scrollTop"></div>
    <span v-if="flash.loading">
      <loading></loading>
    </span>
    <header class="app-header navbar">
      <button class="navbar-toggler mobile-sidebar-toggler d-lg-none" type="button" @click="mobileSidebarToggle">&#9776;</button>
      <b-link class="navbar-brand" to="#"></b-link>
      <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" @click="sidebarMinimize">&#9776;</button>
      <!--<b-nav is-nav-bar class="d-md-down-none">
        <b-nav-item class="px-3">Dashboard</b-nav-item>
        <b-nav-item class="px-3">Users</b-nav-item>
        <b-nav-item class="px-3">Settings</b-nav-item>
      </b-nav>-->
      <b-nav is-nav-bar class="ml-auto">
        <b-nav-item @click="asideToggle" class="d-md-down-none">
          <i class="icon-bell"></i><span class="badge badge-pill badge-danger">{{countNotification}}</span>
        </b-nav-item>
        <!--<b-nav-item class="d-md-down-none">
          <i class="icon-list"></i>
        </b-nav-item>
        <b-nav-item class="d-md-down-none">
          <i class="icon-location-pin"></i>
        </b-nav-item>-->
        <b-nav-item-dropdown right>
          <template slot="button-content">
            <img src="static/img/avatars/6.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
            <span class="d-md-down-none">admin</span>
          </template>
          <b-dropdown-header tag="div" class="text-center"><strong>Account</strong></b-dropdown-header>
          <!--<b-dropdown-item><i class="fa fa-bell-o"></i> Updates<span class="badge badge-info">42</span></b-dropdown-item>
          <b-dropdown-item><i class="fa fa-envelope-o"></i> Messages<span class="badge badge-success">42</span></b-dropdown-item>
          <b-dropdown-item><i class="fa fa-tasks"></i> Tasks<span class="badge badge-danger">42</span></b-dropdown-item>
          <b-dropdown-item><i class="fa fa-comments"></i> Comments<span class="badge badge-warning">42</span></b-dropdown-item>
          <b-dropdown-header tag="div" class="text-center"><strong>Settings</strong></b-dropdown-header>-->
          <b-dropdown-item @click="viewProfile()"><i class="fa fa-user"></i> Profile</b-dropdown-item>
          <b-dropdown-item @click="viewSetting()"><i class="fa fa-wrench"></i> Settings</b-dropdown-item>
          <!--<b-dropdown-item><i class="fa fa-usd"></i> Payments<span class="badge badge-default">42</span></b-dropdown-item>
          <b-dropdown-item><i class="fa fa-file"></i> Projects<span class="badge badge-primary">42</span></b-dropdown-item>
          <b-dropdown-divider></b-dropdown-divider>
          <b-dropdown-item><i class="fa fa-shield"></i> Lock Account</b-dropdown-item>-->
          <b-dropdown-item @click="logout()"><i class="fa fa-lock"></i> Logout</b-dropdown-item>
        </b-nav-item-dropdown>
      </b-nav>
      <button class="navbar-toggler aside-menu-toggler d-md-down-none" type="button" @click="asideToggle">&#9776;</button>
    </header>
  </span>
</template>
<script>

  import Loading from './Loading.vue'
  import Flash from '../../services/flash'
  import {fetchList, updateData} from '../../api/merchantAccount/accountNotification'

export default {
  name: 'header',
  data(){
    return{
      loading: false,
      countNotification: 0,
      userId: '',
      flash: Flash.state
    }
  },
  components: {
    Loading
  },
  created(){
    this.getNotification()
    if(!localStorage.getItem('token')){
      this.$router.push('/pages/login')
      localStorage.removeItem('token')
    }
    this.userId = localStorage.getItem('userId')
    Flash.setLoading(this.loading)
  },
  methods: {
    getNotification() {
      fetchList().then(response => {
        this.countNotification = response['total']
      })
    },
    viewProfile(){
      var userId = localStorage.getItem('userId')
      this.$router.push('/user/'+userId)
    },
    viewSetting(){
      this.$router.push('/setting/configuration')
    },
    logout(){
      window.location.href="auth/logout";
      localStorage.removeItem('token')
      // this.$router.push('pages/login')
    },
    sidebarToggle (e) {
      e.preventDefault()
      document.body.classList.toggle('sidebar-hidden')
    },
    sidebarMinimize (e) {
      e.preventDefault()
      document.body.classList.toggle('sidebar-minimized')
    },
    mobileSidebarToggle (e) {
      e.preventDefault()
      document.body.classList.toggle('sidebar-mobile-show')
    },
    asideToggle (e) {
      e.preventDefault()
      document.body.classList.toggle('aside-menu-hidden')
    }
  }
}
</script>
