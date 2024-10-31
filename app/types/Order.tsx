import { CustomerType } from './Customer';
import { ServiceType } from './Service';
import { ProductType } from './Product';

export type OrderType = {
  id: number;
  administrativeID: number;
  statusID: number;
  contractID: number | null;
  start_time: string;
  end_time: string | null;
  programmed_date: string;
  execution: string | null;
  areas: string[] | null;
  additional_comments: string | null;
  price: number | null;
  type: number | null;
  hasDevices: boolean;
  updated_at: string;
  customer: CustomerType;
  servicesID: number[];
};
