<template>
    <div>
        <vacancies-chooser v-on:vacancyHasBeenSelected="vacancyHasBeenSelected"></vacancies-chooser>
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
  import VacanciesChooser from './VacanciesChooser.vue'
  import Form from 'acacha-forms'

  export default {
    components: {
      UsersChooser,
      VacanciesChooser,
    },
    data () {
      return {
        showUserChooser: false,
        vacancyId: null,
        form: new Form({ userId: null})
      }
    },
    methods: {
      isAssignDisabled() {
        if (this.vacancyId && this.form.userId) return false
        return true
      },
      teacherHasBeenSelected(vacancy) {
        if (teacher) {
          this.showUserChooser = true
          this.vacancyId = vacancy
        }
      },
      userHasBeenSelected(user) {
        if (user) this.form.userId = user
      },
      assign() {
        this.submit()
      },
      submit() {
        this.form.post('/teacher/' + this.vacancyId + '/user')
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
