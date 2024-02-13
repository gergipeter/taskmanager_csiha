<script>
import { defineComponent, ref } from 'vue';
import { apiService } from '@/utils/apiService';
import { showToast } from '@/utils/toast';

export default defineComponent ({
  props: {
    taskUpdatedCallback: Function,
    showEditModal: {
      type: Boolean,
      required: true
    },
    item: {
      type: Object,
      required: true
    }
  },
  setup(props) {
    const isDialogVisible = ref(props.showEditModal);

    const validationErrors = ref({});
    const oldDescription = props.item.description;

    const submitEditTask = async () => {
      try {
        const response = await apiService.put(`tasks/${props.item.id}`, props.item);
        isDialogVisible.value = false;
        if (typeof props.taskUpdatedCallback === 'function') {
          props.taskUpdatedCallback(response.data.task);
        }
        showToast(`Task successfully updated: ${oldDescription} -> ${props.item.description}`, 'success');
      } catch (error) {
        validationErrors.value = error.response.data.errors;
        showToast('General error!', 'error');
      }
    };

    const cancelEdit = () => {
      isDialogVisible.value = false;
     // this.$emit('update:showEditModal', false);
    };

    const getValidationErrors = (field) => {
      return validationErrors.value[field] || [];
    };

    return {
      isDialogVisible,
      cancelEdit,
      validationErrors,
      submitEditTask,
      getValidationErrors
    };
  }
});
</script>

<template>
  <v-dialog v-model="isDialogVisible" :style="{ 'max-width': '600px' }">
    <v-card>
      <v-card-title>Edit Task ID: {{ item.id }} </v-card-title>
      <v-card-text>
        <v-form ref="editTaskForm" @submit.prevent="submitEditTask">
          <v-text-field v-model="item.description" label="Description" :error-messages="getValidationErrors('description')"></v-text-field>
          <v-text-field v-model="item.estimated_time" label="Estimated Time" :error-messages="getValidationErrors('estimated_time')"></v-text-field>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="cancelEdit">Cancel</v-btn>
        <v-btn color="blue darken-1" text @click="submitEditTask">Save</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
