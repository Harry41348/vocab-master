import { Factory } from 'fishery';
import { User } from '@/api/resources/types';
import { faker } from '@faker-js/faker';

export const userFactory = Factory.define<User>(() => ({
  name: faker.person.fullName(),
  email: faker.internet.email(),
}));
