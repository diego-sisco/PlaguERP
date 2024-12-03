import { QuestionType } from "./Question";

export type DeviceType = {
    id: number;
    floorplanID: number;
    name: string;
    number: number;
    area: string;
    version: number;
    code: string;
    is_scanned: boolean;
    is_product_changed: boolean;
    is_device_changed: boolean;
    updated_at: number;
    questions: QuestionType[];
};
