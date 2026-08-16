import { apiFetch } from '@/shared/lib/api';
import { User } from '@/shared/types';

export const userService = {
  getProfile: () => apiFetch<{ user: User }>('/auth/me'),
  updateProfile: (data: { name?: string; email?: string; password?: string }) =>
    apiFetch<{ user: User; message: string }>('/users/me', {
      method: 'PUT',
      body: JSON.stringify(data),
    }),
  getUsers: () => apiFetch<User[]>('/users'),
};
