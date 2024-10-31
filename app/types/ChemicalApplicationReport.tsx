import { Resource } from "./Resource";
import { ServicePest } from "./ServicePest";

export type ChemicalApplicationReport = {
  orderID: number;
  recs: string | null; // recomments
  tech_obs: string | null; // technical_observation
  comments: string | null; // comments
  signature: string | null;
  signatureName: string | null;
  completed_date: string | null;
  end_time: string | null;
  resources: Resource[];
  pests: ServicePest[];
  is_sending: boolean; 
  user_id: string | number | null;
}