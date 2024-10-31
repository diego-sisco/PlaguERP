//import { DomesticReport } from '../../types/ChemicalApplicationReport';
//import { IndustrialReport } from '../../types/DevicesReport';

import { ChemicalApplicationReport } from '../../types/ChemicalApplicationReport';
import { DevicesReport } from '../../types/DevicesReport';
import {axiosInstance, getToken} from '../helpers';

export async function setChemicalApplicationsReports(data: ChemicalApplicationReport[]) {
  try {
    const csrfToken = await getToken();
    const response = await axiosInstance.post('report/chemicalapplications', data, {
      headers: {
        'X-CSRF-TOKEN': csrfToken,
      },
    });
    console.log(`[Chemical App Reports]: Status -> [${response.data}]`);
    return response.data;
  } catch (error) {
    console.error('Error POST para APLICACIÓN QUIMICA:', error);
    throw error;
  }
}

export async function setDevicesReports(data: DevicesReport[]) {
  try {
    const csrfToken = await getToken();
    console.log(csrfToken);
    const response = await axiosInstance.post('report/devices', data, {
      headers: {
        'X-CSRF-TOKEN': csrfToken,
      },
    });
    console.log(`[Devices Reports]: Status -> [${response.data}]`);
    return response.data;
  } catch (error) {
    console.error('Error POST para revision de DISPOSITIVOS:', error);
    throw error;
  }
}