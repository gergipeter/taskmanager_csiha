<script>
import { ref } from 'vue';
import { apiService } from '@/utils/apiService';
import { showToast } from '@/utils/toast';

export default {
  props: {
    taskAddedCallback: Function
  },
  setup(props) {
    const addTaskDialog = ref(false);
    const savingTask = ref(false);
    const newTask = ref({
      description: '',
      estimated_time: ''
    });
    const validationErrors = ref({});

    const submitAddTask = async () => {
      savingTask.value = true;

      try {
        const response = await apiService.post('tasks', newTask.value);
        if (typeof props.taskAddedCallback === 'function') {
          props.taskAddedCallback();
        }
        showToast(`Task successfully created: ${newTask.value.description}`, 'success');
        resetForm();
      } catch (error) {
        if (error.response && error.response.status === 422) {
          validationErrors.value = error.response.data.errors;
        } else {
          showToast('General error!', 'error');
        }
      } finally {
        savingTask.value = false;
      }
    };

    const getValidationErrors = (field) => {
      return validationErrors.value[field] || [];
    };

    const resetForm = () => {
      Object.assign(newTask.value, { description: '', estimated_time: '' });
      validationErrors.value = {};
      addTaskDialog.value = false;
    };

    return {
      addTaskDialog,
      newTask,
      validationErrors,
      submitAddTask,
      getValidationErrors,
      savingTask
    };
  }
};
</script>

<template>
  <v-dialog v-model="addTaskDialog" max-width="600px">
    <template v-slot:activator="{ on }">
      <v-btn color="primary" dark @click="addTaskDialog = true"><v-icon>mdi-plus</v-icon> Add Task</v-btn>
    </template>
    <v-card>
      <v-card-title>Add Task</v-card-title>
      <v-card-text>
        <v-form @submit.prevent="submitAddTask">
          <v-text-field v-model="newTask.description" label="Description" :error-messages="getValidationErrors('description')"></v-text-field>
          <v-text-field v-model="newTask.estimated_time" label="Estimated Time (in seconds)" :error-messages="getValidationErrors('estimated_time')"></v-text-field>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="addTaskDialog = false">Cancel</v-btn>
        <v-btn color="blue darken-1" text @click="submitAddTask" :disabled="savingTask">
          <span v-if="!savingTask">Save</span>
          <span v-else>Saving...</span>
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>