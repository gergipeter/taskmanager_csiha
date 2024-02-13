<script>
import { ref, computed } from 'vue';
import { apiService } from '@/utils/apiService';
import { showToast } from '@/utils/toast';

import AddTaskModal from './AddTaskModal.vue';
import EditTaskModal from './EditTaskModal.vue';

export default {
  components: {
    AddTaskModal,
    EditTaskModal,
  },
  setup() {
    const headers = ref([
      { title: 'ID', key: 'id', sortable: true },
      { title: 'Description', key: 'description', sortable: true },
      { title: 'Estimated Time', key: 'estimated_time', sortable: true},
      { title: 'Elapsed Time', key: 'elapsed_time', sortable: true},
      { title: 'Used Time', key: 'used_time', sortable: true},
      { title: 'User Name', key: 'user.name', sortable: true },
      { title: 'User Email Address', key: 'user.email', sortable: true },
      { title: 'Created At', key: 'created_at', sortable: true},
      { title: 'Completed At', key: 'completed_at', sortable: true},
      { title: 'Actions', key: 'actions', sortable: false }
    ]);

    const newTask = ref({
      description: '',
      estimated_time: ''
    });

    const items = ref([]);
    const search = ref('');
    const loading = ref(false);
    const addTaskDialog = ref(false);
    const confirmDialog = ref(false);
    const selected = ref([]);
    const dialogQuestion = ref(null);
    const dialogAction = ref(null);
    const elapsedTimeUpdater = ref(null);
    const validationErrors = ref({});

    const totalEstimatedTime = computed(() => {
      return calculateTotalEstimatedTime();
    });

    const totalElapsedTime = computed(() => {
      return calculateTotalElapsedTime();
    });

    const showEditModal = (item) => {
      console.log('hahao');
      item.showEditModal = true;
    };
        
    const loadData = async () => {
      loading.value = true;
      try {
        const response = await apiService.get('tasks');
        items.value = response.data;
      } catch (error) {
        console.error('Error fetching data:', error);
      } finally {
        loading.value = false;
      }
    };

    const startElapsedTimeUpdater = () => {
      elapsedTimeUpdater.value = setInterval(() => {
        items.value.forEach(item => {
          if (item && item.created_at) {
            item.elapsed_time = calculateElapsedTime(item.created_at);
          }
        });
      }, 1000);
    };

    const calculateElapsedTime = (created_at) => {
      const currentTime = new Date();
      const createdAtTime = new Date(created_at);
      const elapsedMilliseconds = currentTime - createdAtTime;
      return Math.floor(elapsedMilliseconds / 1000);
    };

    const calculateTotalElapsedTime = () => {
      return selected.value.reduce((total, row) => total + parseFloat(row.elapsed_time), 0);
    };

    const calculateTotalEstimatedTime = () => {
        return selected.value.reduce((total, row) => total + parseFloat(row.estimated_time), 0);
    };

    const formatTime = (seconds) => {
      const hours = Math.floor(seconds / 3600);
      const minutes = Math.floor((seconds % 3600) / 60);
      const remainingSeconds = seconds % 60;
      const formattedMinutes = String(minutes).padStart(2, '0');
      const formattedSeconds = String(remainingSeconds).padStart(2, '0');

      return `${hours}:${formattedMinutes}:${formattedSeconds}`;
    };

    const formatDate = (date) => {
      return date ? new Date(date).toLocaleString() : '';
    };

    const searchItems = (search) => {
      search.value = search;
    };

    const isDisabled = (item) => {
      return item.completed_at !== null;
    }
    
    const massUpdate = async () => {
      const selectedRows = selected.value.map(row => row.id);
      confirmDialog.value = false;
      try {
          await apiService.put('tasks/mass-complete', selectedRows);
          await loadData();
          selected.value = [];
          showToast(`Task ID: [${selectedRows}] marked as complete.`, 'success');
      } catch (error) {
          showToast('General error!', 'error');
      }
    };

    const massDestroy = async () => {
      const selectedRows = selected.value.map(row => row.id);
      confirmDialog.value = false;
      try {
          await apiService.post('tasks/mass-destroy', selectedRows);
          await loadData();
          selected.value = [];
          const isPlurar = (selectedRows.length) > 1 ? 'are' : 'is'; 
          showToast(`Task ID: [${selectedRows}] ${isPlurar} deleted.`, 'success');
      } catch (error) {
          showToast('General error!', 'error');
      }
    };

    const deleteItem = async (item) => {
      confirmDialog.value = false;
      try {
        const response = await apiService.delete(`tasks/${item.id}`);
        await loadData();
        showToast(`Task successfully deleted: ${item.description}`, 'success');
      } catch (error) {
        showToast('General error!', 'error');
      }
    };

  const confirmAction = async (action, item) => {
    try {
      confirmDialog.value = true;
      if (action === 'massComplete') {
        await massUpdate();
      } else if (action === 'massDestroy') {
        await massDestroy();
      } else if (action === 'deleteItem') {
        await deleteItem(item);
      }
    } catch (error) {
      showToast('General error!', 'error');
    }
  }; 

  const handleConfirmDialog = (action, item) => {
    confirmDialog.value = true;
    if (action === 'massComplete') {
        dialogQuestion.value = `Are you sure you want to mark these selected row as completed?`;
    } else if (action === 'massDestroy') {
        dialogQuestion.value = `Are you sure you want to delete these selected rows?`;
    } else if (action === 'deleteItem') {
      dialogQuestion.value = `Are you sure you want to delete this row?`;
    }
    dialogAction.value = action;
  };

    const isSelected = (item) => {
      return selected.value.includes(item);
    };

    const shortenDescription = (description) => {
    const maxLength = 50;
    if (description.length > maxLength) {
      return description.substring(0, maxLength) + '...';
    }
    return description;
  }

    return {
      headers,
      items,
      search,
      loading,
      addTaskDialog,
      newTask,
      selected,
      elapsedTimeUpdater,
      validationErrors,
      totalEstimatedTime,
      totalElapsedTime,
      loadData,
      startElapsedTimeUpdater,
      calculateElapsedTime,
      calculateTotalEstimatedTime,
      calculateTotalElapsedTime,
      formatTime,
      searchItems,
      showEditModal,
      formatDate,
      massUpdate,
      massDestroy,
      isDisabled,
      isSelected,
      confirmDialog,
      confirmAction,
      handleConfirmDialog,
      shortenDescription,
      dialogQuestion,
      dialogAction
    };
  },
  mounted() {
    this.loadData();
    this.startElapsedTimeUpdater();
  }
};
</script>

<template>
  <v-app>
    <v-container fluid class="fixed-width-datatable">

      <!-- Custom Actions -->
      <v-row class="display-flex v-row-custom-actions">
      <!-- Summary -->
      <v-col v-if="selected.length > 0" class="flex-grow-1">
        <span v-if="selected.length > 1" class="sum-style">Σ</span> Estimated Time: {{ formatTime(totalEstimatedTime) }}
      </v-col>
      <v-col v-if="selected.length > 0" class="flex-grow-1">
        <span v-if="selected.length > 1" class="sum-style">Σ</span> Elapsed Time: {{ formatTime(totalElapsedTime) }}
      </v-col>
      <v-col class="flex-grow-1">
        <v-btn v-if="selected.length > 0" color="primary" dark  @click="handleConfirmDialog('massComplete')">
          <v-icon v-if="selected.length === 1">mdi-check</v-icon>
          <v-icon v-else>mdi-check-all</v-icon>
          <span v-if="selected.length === 1">Mark Row Completed</span>
          <span v-else>Mark Rows Completed</span>
        </v-btn>
      </v-col>
      <v-col class="justify-end flex-grow-1">
        <v-btn v-if="selected.length > 0" color="primary" dark @click="handleConfirmDialog('massDestroy')">
          <v-icon v-if="selected.length === 1">mdi-close-box</v-icon>
          <v-icon v-else>mdi-close-box-multiple</v-icon>
          <span v-if="selected.length === 1">Delete Row</span>
          <span v-else>Delete Rows</span>
        </v-btn>
      </v-col>
    </v-row>

      <!-- Search Bar  -->
      <v-row class="mb-1">
        <v-col>
          <v-text-field
            v-model="search"
            label="Search..."
            prepend-inner-icon="mdi-magnify"
            single-line
            variant="outlined"
            hide-details
          ></v-text-field>
        </v-col>
      </v-row>

      <!-- Add Task Modal -->
      <AddTaskModal
        :task-added-callback="loadData"
      />

      <!-- Data Table -->
      <v-row class="mt-1">
        <v-col>
          <v-data-table
            v-model="selected"
            :headers="headers"
            :items="items"
            :search="search"
            :loading="loading"
            :items-per-page="5"
            show-select
            dense
            :search-props="{ 'label': 'Search by any column' }"
            @update:search="searchItems"
          >
          <template v-slot:header.data-table-select> </template> 
          <template v-slot:item="{ item }">
            <tr :class="{ 'custom-bg': !item.completed_at, 'completed-row': item.completed_at, 'selected-bg': isSelected(item)}">
              <td>
                <v-checkbox class="mt-5" v-model="selected" :value="item" :disabled="isDisabled(item)"></v-checkbox>
              </td>
              <td>
                <span>{{ item.id }}</span>
              </td>
              <td>
                <span :class="{ 'completed-row': item.completed_at}" class="description-tooltip" :title="item.description">{{ shortenDescription(item.description) }}</span>
              </td>
              <td>
                <span>{{ formatTime(item.estimated_time) }}</span>
              </td>
              <td>
                <span v-if="!item.completed_at">{{ formatTime(item.elapsed_time) }}</span>
                <span v-else>Completed</span>
              </td>
              <td>
                <span v-if="item.completed_at">{{ formatTime(item.used_time) }}</span>
              </td>
              <td>
                <span>{{ item.user.name }}</span>
              </td>
              <td>
                <span>{{ item.user.email }}</span>
              </td>
              <td>
                <span>{{ formatDate(item.created_at) }}</span>
              </td>
              <td>
                <span>{{ formatDate(item.completed_at) }}</span>
              </td>
              <td v-if="item.completed_at" :style="{ 'pointer-events': 'none' }">
                  <v-icon>mdi-pencil-off</v-icon>
                  <v-icon>mdi-delete-off</v-icon>
              </td>
              <td v-else>
                <v-icon @click="showEditModal(item)">mdi-pencil</v-icon>
                <v-icon @click="handleConfirmDialog('deleteItem', item)">mdi-delete</v-icon>
              </td>
            </tr>

            <!-- Edit Task Modal -->
            <EditTaskModal
              v-if="item.showEditModal"
              :show-edit-modal="item.showEditModal"
              :item="item"
              @update:showEditModal="showEditModal"
              :task-updated-callback="loadData"
            />

            <v-dialog v-model="confirmDialog" max-width="400">
              <v-card>
                <v-card-title>Confirm Action</v-card-title>
                <v-card-text>{{ dialogQuestion }}</v-card-text>
                <v-card-actions >
                  <v-btn class="confirmation-dialog-buttons" color="error" text @click="confirmDialog = false">Cancel</v-btn>
                  <v-btn class="confirmation-dialog-buttons" color="primary" text @click="confirmAction(dialogAction, item)">Confirm</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
            
          </template>
          </v-data-table>
        </v-col>
      </v-row>
    </v-container>
  </v-app>
</template>