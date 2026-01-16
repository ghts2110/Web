<script setup lang="ts">
    import { ref } from 'vue';
    import { useForm } from "@inertiajs/vue3";

    type PromptVersion = { id: number; prompt_id: number; name: string; content: string }
    type DbPrompt = {id: number; name: string};

    const showModalAdd = ref(false);
    const showModalPromptEdit = ref(false);
    const showModalPromptVersionEdit = ref(false);
    const showPromptVersion = ref(false);

    const promptSelected = ref<DbPrompt>();
    const promptVersionSelected = ref<PromptVersion|null>(null);

    const form = useForm({
        name: '',
        content: '',
    })

    const props = defineProps<{
        prompts: DbPrompt[];
        promptsVersion: PromptVersion[];
    }>()
    
    function addPrompt() {
        form.post('/prompt-versions/', {
            onSuccess: () => {
                form.reset()
                showModalAdd.value = false
            },
        })
    }

    function savePrompt(){
        form.post(`/prompt-versions/${promptSelected.value?.id}`, {
            onSuccess: () => {
                promptVS.value = props.promptsVersion.filter(v => v.prompt_id === promptSelected.value?.id);
            },
        })
    }

    const promptVS = ref<PromptVersion[]>([]);
    function promptVersionsSelected(prompt: DbPrompt) {
        promptSelected.value = prompt;
        promptVersionSelected.value = null;
        showPromptVersion.value = true;
        promptVS.value = props.promptsVersion.filter(v => v.prompt_id === promptSelected.value?.id);
    }

    const promptEdit = ref<DbPrompt|PromptVersion>();

    function editPrompt(){
        form.patch(`/prompt/${promptEdit.value?.id}`, {
            onSuccess: () => {
                form.reset()
                showModalPromptEdit.value = false;
            }
        });
    }

    function editPromptVersion(){
        console.log(promptEdit)
        form.patch(`/prompt-versions/${promptEdit.value?.id}`, {
            onSuccess: () => {
                form.reset()
                showModalPromptVersionEdit.value = false;
                promptVS.value = props.promptsVersion.filter(v => v.prompt_id === promptSelected.value?.id);
            }
        });
    }

    function removePrompt() {
        form.delete(`/prompts/${promptEdit.value?.id}`, {
            onSuccess: () => {
                form.reset()
                showModalPromptEdit.value = false;
                showPromptVersion.value = false;
            }
        });
    }

    function removePromptVersion() {
        form.delete(`/prompt-versions/${promptEdit.value?.id}`, {
            onSuccess: () => {
                form.reset()
                showModalPromptVersionEdit.value = false;
                promptVS.value = props.promptsVersion.filter(v => v.prompt_id === promptSelected.value?.id);
            }
        });
    }
</script>

<template>
    <div class="w-full container">
        <div class="mx-10 py-5 flex justify-between">
            <h1 class="text-4xl">Prompts</h1>

            <button class="bg-black text-white px-4 py-2 rounded-xl text-xl" @click="showModalAdd = true">+ Add</button>
        </div>
    
    </div>

    <div v-if="showModalAdd" @click.self="showModalAdd = false" class="fixed inset-0 bg-black/40 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-lg bg-white p-4 shadow">
            <h2 class="text-2xl">Novo prompt</h2>

            <form class="mt-3 space-y-3" @submit.prevent="addPrompt">
                <input v-model="form.name" required placeholder="Nome do prompt" class="w-full rounded-md border px-3 py-2" type="text">
                
                <div class="flex justify-end gap-2">
                    <button type="button" class="text-lg rounded-md px-4 py-2 border" @click.self="showModalAdd = false">Cancelar</button>
                    <button type="submit" class="text-lg rounded-md bg-black text-white px-4 py-2">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <div v-if="showModalPromptEdit" @click.self="showModalPromptEdit = false" class="fixed inset-0 bg-black/40 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-lg bg-white p-4 shadow">
            <h2 class="text-2xl">{{promptEdit?.name}}</h2>

            <form @submit.prevent="editPrompt" class="mt-3 space-y-3">
                <input type="text" v-model="form.name" required placeholder="Mudar nome" class="w-full rounded-md border px-3 py-2">
                
                <div class="flex justify-between">
                    <button @click="removePrompt()" type="button" class="text-lg rounded-md bg-red-500 text-white px-4 py-2">Deletar</button>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="text-lg rounded-md px-4 py-2 border" @click.self="showModalPromptEdit = false">Cancelar</button>
                        <button type="submit" class="text-lg rounded-md bg-black text-white px-4 py-2">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div v-if="showModalPromptVersionEdit" @click.self="showModalPromptEdit = false" class="fixed inset-0 bg-black/40 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-lg bg-white p-4 shadow">
            <h2 class="text-2xl">{{promptEdit?.name}}</h2>

            <form @submit.prevent="editPromptVersion" class="mt-3 space-y-3">
                <input type="text" v-model="form.name" required placeholder="Mudar nome" class="w-full rounded-md border px-3 py-2">
                
                <div class="flex justify-between">
                    <button @click="removePromptVersion()" type="button" class="text-lg rounded-md bg-red-500 text-white px-4 py-2">Deletar</button>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="text-lg rounded-md px-4 py-2 border" @click.self="showModalPromptEdit = false">Cancelar</button>
                        <button type="submit" class="text-lg rounded-md bg-black text-white px-4 py-2">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="w-full container">
        <div class="mx-10 flex space-x-5">
            <ul class="space-y-3">
                <li v-for="prompt in prompts" :key="prompt.id" class="flex border rounded-lg py-2 px-4 text-lg w-70">
                    <button @click="promptVersionsSelected(prompt)" class="w-full text-start">{{ prompt.name }}</button>
                    <button @click="promptEdit = prompt, showModalPromptEdit = true" class="w-7"> &#8942; </button>
                </li>
            </ul>

            <ul v-if="showPromptVersion" class="space-y-3">
                <li v-for="promptVersion in promptVS" :key="promptVersion.id" class="flex border rounded-lg py-2 px-4 text-lg w-70">
                    <button @click="promptVersionSelected = promptVersion, form.content = promptVersion.content" class="w-full text-start">{{ promptVersion.name }}</button>
                    <button @click="promptEdit = promptVersion, showModalPromptVersionEdit = true" class="w-7"> &#8942; </button>
                </li>
            </ul>

            <div v-if="promptVersionSelected" class="w-full fle flex-col">
                <h2 class="text-2xl">{{ promptVersionSelected.name }}</h2>
                <textarea v-model="form.content" class="border rounded-2xl w-full h-190"></textarea>

                <div class="flex justify-end space-x-8 p-5">
                    <button type="button" @click="form.content = ''" class="text-lg rounded-md px-4 py-2 border">Limpar</button>
                    <button @click="savePrompt" class="text-lg rounded-md bg-black text-white px-4 py-2">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</template>