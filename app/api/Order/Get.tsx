import AsyncStorage from '@react-native-async-storage/async-storage';
import {axiosInstance} from '../helpers';

export async function getOrders(date: string) {
  try {
    const id = await AsyncStorage.getItem('userID');
    const url = `order/getData/${id}/${date}`;
    const response = await axiosInstance.get(url);
    return response.data;
  } catch (error) {
    console.error('Error al realizar la solicitud:', error);
    throw error;
  }
}
