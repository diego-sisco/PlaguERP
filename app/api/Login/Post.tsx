import {axiosInstance, getToken} from '../helpers';

type Authentication = {
  email: string;
  password: string;
};

export async function loginAuth(data: Authentication) {
  try {
    const csrfToken = await getToken();
    const response = await axiosInstance.post('login/auth', data, {
      headers: {
        'X-CSRF-TOKEN': csrfToken,
      },
    });
    return response.data;
  } catch (error) {
    console.error('Error al realizar la solicitud POST:', error);
    throw error;
  }
}
