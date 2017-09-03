<template>
    <div>
        <teachers-chooser v-on:teacherHasBeenSelected="teacherHasBeenSelected"></teachers-chooser>
        <users-chooser v-if="showUserChooser" v-on:userHasBeenSelected="userHasBeenSelected"></users-chooser>
        <br/>
        <div style="text-align: center;">
            <a class="btn btn-primary" v-on:click="assign" :disabled="isAssignDisabled()" >
                <i class="fa fa-hand-pointer-o"></i> Assign
            </a>
        </div>
    </div>
</template>

<script>

  import UsersChooser from '../users/UsersChooser.vue'
  import TeachersChooser from './TeachersChooser.vue'
  import Form from 'acacha-forms'

  export default {
    components: {
      UsersChooser,
      TeachersChooser,
    },
    data () {
      return {
        showUserChooser: false,
        teacherId: null,
        form: new Form({ userId: null})
      }
    },
    methods: {
      isAssignDisabled() {
        if (this.teacherId && this.form.userId) return false
        return true
      },
      teacherHasBeenSelected(teacher) {
        if (teacher) {
          this.showUserChooser = true
          this.teacherId = teacher
        }
      },
      userHasBeenSelected(user) {
        if (user) this.form.userId = user
      },
      assign() {
        this.submit()
      },
      submit() {
        this.form.post('/teacher/' + this.teacherId + '/user')
          .then( response => {
            console.log('done!')
            //do what you need to do if register is ok
          })
          .catch( error => {
            console.log('error!')
            console.log(form.errors.all())
          })
      }
    },
    mounted() {
        console.log('Component AssignUsersToTeacher mounted.')
    }
  }
</script>
